<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\LoginController;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Renewal;
use App\Models\Role;
use App\Models\Room;
use App\Models\Ticket;
use App\Models\TaxReport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class OwnerController extends Controller
{
    /**
     * Threshold tahunan bebas PPh Final untuk WP Orang Pribadi.
     * Dasar hukum: PP No. 55 Tahun 2022, Pasal 7 ayat (2a) — pengganti PP No. 23 Tahun 2018.
     * Berlaku PER TAHUN (akumulasi 1 Januari s.d. 31 Desember), BUKAN per bulan.
     */
    private const TAX_THRESHOLD_OP = 500_000_000;

    /** Batas atas omzet UMKM agar tetap berhak memakai tarif final 0,5% */
    private const TAX_UMKM_CEILING = 4_800_000_000;

    /** Tarif PPh Final UMKM */
    private const TAX_RATE = 0.005;

    public function dashboard()
    {
        $stats = [
            'total_rooms'      => Room::count(),
            'available_rooms'  => Room::where('status', 'available')->count(),
            'occupied_rooms'   => Room::where('status', 'occupied')->count(),
            'total_tenants'    => User::whereHas('role', fn($q) => $q->where('name', 'tenant'))->count(),
            'total_admins'     => User::whereHas('role', fn($q) => $q->where('name', 'admin'))->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
        ];

        $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))
            ->orderBy('created_at', 'desc')
            ->limit(5)->get();

        return view('owner.dashboard', compact('stats', 'admins'));
    }

    public function listAdmins(Request $request)
    {
        $query = User::whereHas('role', fn($q) => $q->where('name', 'admin'))
            ->orderBy('created_at', 'desc');

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->status === 'active') {
            $query->where('is_active', true);
        } elseif ($request->status === 'inactive') {
            $query->where('is_active', false);
        }

        $admins = $query->paginate(10)->withQueryString();
        return view('owner.admins.index', compact('admins'));
    }

    public function createAdmin()
    {
        return view('owner.admins.create');
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:25',
        ]);

        $role = Role::where('name', 'admin')->first();
        $user = User::create([
            'role_id'    => $role->id,
            'name'       => trim($request->name),
            'email'      => strtolower(trim($request->email)),
            'phone'      => $request->phone,
            'is_active'  => true,
            'created_by' => auth()->id(),
        ]);

        LoginController::generateAndSendOtp($user->email, 'login', $user->name);

        return redirect()->route('owner.admins.index')
            ->with('success', 'Admin berhasil ditambahkan. OTP login dikirim ke ' . $user->email);
    }

    public function editAdmin($id)
    {
        $admin = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->findOrFail($id);
        return view('owner.admins.edit', compact('admin'));
    }

    public function updateAdmin(Request $request, $id)
    {
        $admin = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->findOrFail($id);

        $request->validate([
            'name'  => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:25',
        ]);

        $admin->update([
            'name'  => trim($request->name),
            'email' => strtolower(trim($request->email)),
            'phone' => $request->phone,
        ]);

        return redirect()->route('owner.admins.index')
            ->with('success', 'Data admin ' . $admin->name . ' berhasil diperbarui.');
    }

    public function toggleAdmin($id)
    {
        $admin = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->findOrFail($id);
        $admin->update(['is_active' => !$admin->is_active]);
        $status = $admin->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun admin {$admin->name} berhasil {$status}.");
    }

    public function destroyAdmin($id)
    {
        $admin = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->findOrFail($id);
        $admin->delete();
        return back()->with('success', 'Akun admin berhasil dihapus.');
    }

    public function analytics(Request $request)
    {
        $year  = (int)($request->year  ?? now()->year);
        $month = (int)($request->month ?? now()->month);
        $years = range(now()->year, now()->year - 3, -1);

        $totalRevenue    = Invoice::where('status', 'paid')->whereYear('period_start', $year)->sum('total_amount');
        $monthRevenue    = Invoice::where('status', 'paid')->whereYear('period_start', $year)->whereMonth('period_start', $month)->sum('total_amount');
        $paidInvoices    = Invoice::where('status', 'paid')->whereYear('created_at', $year)->count();
        $unpaidInvoices  = Invoice::whereIn('status', ['unpaid', 'overdue'])->whereYear('created_at', $year)->count();
        $overdueInvoices = Invoice::where('status', 'overdue')->count();
        $overdueAmount   = Invoice::where('status', 'overdue')->sum('total_amount');

        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[] = [
                'month'      => Carbon::create($year, $m, 1)->format('M'),
                'revenue'    => Invoice::where('status', 'paid')->whereYear('period_start', $year)->whereMonth('period_start', $m)->sum('total_amount'),
                'unpaid'     => Invoice::whereIn('status', ['unpaid', 'overdue'])->whereYear('period_start', $year)->whereMonth('period_start', $m)->sum('total_amount'),
                'invoices'   => Invoice::whereYear('period_start', $year)->whereMonth('period_start', $m)->count(),
                'paid_count' => Invoice::where('status', 'paid')->whereYear('period_start', $year)->whereMonth('period_start', $m)->count(),
            ];
        }

        $revenueByType = Invoice::where('status', 'paid')
            ->whereYear('period_start', $year)
            ->select('invoice_type as type', DB::raw('SUM(total_amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('invoice_type')->get();

        $topRooms = Invoice::where('status', 'paid')
            ->whereYear('period_start', $year)
            ->select('room_id', DB::raw('SUM(total_amount) as total_revenue'), DB::raw('COUNT(*) as invoice_count'))
            ->groupBy('room_id')
            ->with('room:id,room_number,room_name,price')
            ->orderByDesc('total_revenue')
            ->limit(5)->get();

        $cashFlowQuery = Invoice::with(['user:id,name,email', 'room:id,room_number,room_name'])
            ->whereYear('created_at', $year);
        if ($request->filled('cf_month') && $request->cf_month !== 'all') {
            $cashFlowQuery->whereMonth('created_at', (int)$request->cf_month);
        }
        if ($request->filled('cf_status') && $request->cf_status !== 'all') {
            $cashFlowQuery->where('status', $request->cf_status);
        }
        if ($request->filled('cf_type') && $request->cf_type !== 'all') {
            $cashFlowQuery->where('invoice_type', $request->cf_type);
        }
        $cashFlowInvoices = $cashFlowQuery->orderByDesc('created_at')->paginate(20, ['*'], 'cf_page')->withQueryString();

        $cashFlowMonthly = [];
        for ($m = 1; $m <= 12; $m++) {
            $cashFlowMonthly[] = [
                'month'         => Carbon::create($year, $m, 1)->format('M'),
                'masuk'         => Invoice::where('status', 'paid')->whereYear('paid_at', $year)->whereMonth('paid_at', $m)->sum('total_amount'),
                'pending'       => Invoice::whereIn('status', ['unpaid', 'pending_verification'])->whereYear('created_at', $year)->whereMonth('created_at', $m)->sum('total_amount'),
                'overdue_total' => Invoice::where('status', 'overdue')->whereYear('created_at', $year)->whereMonth('created_at', $m)->sum('total_amount'),
            ];
        }

        $allRooms = Room::orderBy('room_number')->get();
        $rooms    = $allRooms;

        $bookingQuery = Booking::with(['user:id,name,email,phone', 'room:id,room_number,room_name,price'])
            ->whereYear('created_at', $year);
        if ($request->filled('bk_room') && $request->bk_room !== 'all') {
            $bookingQuery->where('room_id', (int)$request->bk_room);
        }
        if ($request->filled('bk_status') && $request->bk_status !== 'all') {
            $bookingQuery->where('status', $request->bk_status);
        }
        if ($request->filled('bk_month') && $request->bk_month !== 'all') {
            $bookingQuery->whereMonth('created_at', (int)$request->bk_month);
        }
        $bookingDetail = $bookingQuery->orderByDesc('created_at')->paginate(20, ['*'], 'bk_page')->withQueryString();

        $bookingStatsByRoom = $allRooms->map(function ($room) use ($year) {
            $bookings      = Booking::where('room_id', $room->id)->whereYear('created_at', $year)->get();
            $activeBooking = Booking::where('room_id', $room->id)->whereIn('status', ['active', 'approved'])->first();
            return [
                'room'           => $room,
                'total_bookings' => $bookings->count(),
                'completed'      => $bookings->whereIn('status', ['completed'])->count(),
                'cancelled'      => $bookings->whereIn('status', ['cancelled', 'rejected'])->count(),
                'active'         => $bookings->whereIn('status', ['active', 'approved'])->count(),
                'total_revenue'  => Invoice::where('status', 'paid')->where('room_id', $room->id)->whereYear('period_start', $year)->sum('total_amount'),
                'avg_duration'   => round($bookings->avg('duration_months') ?? 0, 1),
                'current_tenant' => $activeBooking?->user?->name ?? null,
                'last_booking'   => $bookings->sortByDesc('created_at')->first()?->created_at,
                'days_vacant'    => $room->status === 'available'
                    ? (int)(optional($bookings->sortByDesc('updated_at')->first()?->updated_at ?? now())->diffInDays(now()))
                    : 0,
            ];
        })->sortByDesc('total_bookings');

        $leastBookedRooms = collect($bookingStatsByRoom)->sortBy('total_bookings')->take(5)->values();

        $bookingTrendMonthly = [];
        for ($m = 1; $m <= 12; $m++) {
            $bookingTrendMonthly[] = [
                'month'     => Carbon::create($year, $m, 1)->format('M'),
                'total'     => Booking::whereYear('created_at', $year)->whereMonth('created_at', $m)->count(),
                'approved'  => Booking::where('status', 'approved')->whereYear('created_at', $year)->whereMonth('created_at', $m)->count(),
                'cancelled' => Booking::whereIn('status', ['cancelled', 'rejected'])->whereYear('created_at', $year)->whereMonth('created_at', $m)->count(),
            ];
        }

        $totalBookings     = Booking::whereYear('created_at', $year)->count();
        $approvedBookings  = Booking::whereIn('status', ['approved', 'active', 'completed'])->whereYear('created_at', $year)->count();
        $cancelledBookings = Booking::whereIn('status', ['cancelled', 'rejected'])->whereYear('created_at', $year)->count();
        $avgDuration       = round(Booking::whereYear('created_at', $year)->avg('duration_months') ?? 0, 1);
        $totalR            = $allRooms->count();

        $latePaymentRate = $this->calcLatePaymentRate($year, $month);

        $latePayments = Invoice::where('status', 'paid')
            ->whereYear('period_start', $year)
            ->whereRaw('paid_at > due_date')
            ->select(
                DB::raw('MONTH(period_start) as m'),
                DB::raw('COUNT(*) as count'),
                DB::raw('AVG(DATEDIFF(paid_at, due_date)) as avg_days_late'),
                DB::raw('MAX(DATEDIFF(paid_at, due_date)) as max_days_late')
            )
            ->groupBy('m')->orderBy('m')->get()->keyBy('m');

        $latePaymentMonthly = [];
        for ($m = 1; $m <= 12; $m++) {
            $latePaymentMonthly[] = [
                'month'         => Carbon::create($year, $m, 1)->format('M'),
                'late_count'    => $latePayments->get($m)?->count ?? 0,
                'avg_days_late' => round($latePayments->get($m)?->avg_days_late ?? 0, 1),
                'max_days_late' => $latePayments->get($m)?->max_days_late ?? 0,
            ];
        }

        $chronicallyLateUsers = Invoice::where('status', 'paid')
            ->whereYear('period_start', $year)
            ->whereRaw('paid_at > due_date')
            ->select('user_id', DB::raw('COUNT(*) as late_count'), DB::raw('AVG(DATEDIFF(paid_at, due_date)) as avg_days'), DB::raw('SUM(total_amount) as total_paid'))
            ->groupBy('user_id')
            ->having('late_count', '>=', 2)
            ->with('user:id,name,email,phone')
            ->orderByDesc('late_count')
            ->limit(10)->get();

        $lateInvoiceQuery = Invoice::with(['user:id,name,email', 'room:id,room_number'])
            ->where('status', 'paid')
            ->whereYear('period_start', $year)
            ->whereRaw('paid_at > due_date');
        if ($request->filled('lt_month') && $request->lt_month !== 'all') {
            $lateInvoiceQuery->whereMonth('period_start', (int)$request->lt_month);
        }
        $lateInvoiceDetail = $lateInvoiceQuery->orderByDesc(DB::raw('DATEDIFF(paid_at, due_date)'))->paginate(15, ['*'], 'lt_page')->withQueryString();

        $currentOverdue = Invoice::with(['user:id,name,email', 'room:id,room_number'])
            ->where('status', 'overdue')
            ->orderByDesc('due_date')
            ->get();

        $paidCount         = Invoice::where('status', 'paid')->whereYear('created_at', $year)->count();
        $unpaidCount       = Invoice::whereIn('status', ['unpaid', 'overdue'])->whereYear('created_at', $year)->count();
        $lateCount         = Invoice::where('status', 'paid')->whereYear('created_at', $year)->whereRaw('paid_at > due_date')->count();
        $totalInvoicesYear = Invoice::whereYear('created_at', $year)->count();

        $totalTickets    = Ticket::whereYear('created_at', $year)->count();
        $resolvedTickets = Ticket::whereYear('created_at', $year)->where('status', 'resolved')->count();
        $openTickets     = Ticket::whereIn('status', ['open', 'in_progress'])->count();
        $urgentTickets   = Ticket::where('priority', 'urgent')->whereIn('status', ['open', 'in_progress'])->count();
        $avgMttr         = $this->calcAvgMttr($year);
        $avgRating       = round(Ticket::whereYear('created_at', $year)->whereNotNull('rating')->avg('rating') ?? 0, 1);

        $ticketsByCategory = Ticket::selectRaw('category, COUNT(*) as count, AVG(rating) as avg_rating, SUM(CASE WHEN status="resolved" THEN 1 ELSE 0 END) as resolved_count')
            ->whereYear('created_at', $year)->groupBy('category')->get();

        $ticketMonthly = [];
        for ($m = 1; $m <= 12; $m++) {
            $ticketMonthly[] = [
                'month'    => Carbon::create($year, $m, 1)->format('M'),
                'total'    => Ticket::whereYear('created_at', $year)->whereMonth('created_at', $m)->count(),
                'resolved' => Ticket::where('status', 'resolved')->whereYear('created_at', $year)->whereMonth('created_at', $m)->count(),
                'open'     => Ticket::whereIn('status', ['open', 'in_progress'])->whereYear('created_at', $year)->whereMonth('created_at', $m)->count(),
                'urgent'   => Ticket::where('priority', 'urgent')->whereYear('created_at', $year)->whereMonth('created_at', $m)->count(),
            ];
        }

        $ticketsByPriority = Ticket::selectRaw('priority, COUNT(*) as count')
            ->whereYear('created_at', $year)->groupBy('priority')->get()->keyBy('priority');

        $ticketQuery = Ticket::with(['user:id,name,email', 'assignee:id,name', 'replies'])
            ->whereYear('created_at', $year);
        if ($request->filled('tk_status') && $request->tk_status !== 'all') {
            $ticketQuery->where('status', $request->tk_status);
        }
        if ($request->filled('tk_priority') && $request->tk_priority !== 'all') {
            $ticketQuery->where('priority', $request->tk_priority);
        }
        if ($request->filled('tk_category') && $request->tk_category !== 'all') {
            $ticketQuery->where('category', $request->tk_category);
        }
        if ($request->filled('tk_month') && $request->tk_month !== 'all') {
            $ticketQuery->whereMonth('created_at', (int)$request->tk_month);
        }
        $ticketDetail = $ticketQuery->orderByDesc('created_at')->paginate(15, ['*'], 'tk_page')->withQueryString();

        $urgentOpenTickets = Ticket::with(['user:id,name,email', 'assignee:id,name'])
            ->where('priority', 'urgent')
            ->whereIn('status', ['open', 'in_progress'])
            ->orderBy('created_at')
            ->get();

        $ticketCategories = Ticket::whereYear('created_at', $year)->distinct()->pluck('category');

        return view('owner.reports.index', compact(
            'totalRevenue', 'monthRevenue', 'paidInvoices', 'unpaidInvoices',
            'overdueInvoices', 'overdueAmount', 'monthlyData', 'revenueByType', 'topRooms',
            'cashFlowInvoices', 'cashFlowMonthly',
            'rooms', 'allRooms', 'totalR', 'bookingDetail', 'bookingStatsByRoom',
            'leastBookedRooms', 'bookingTrendMonthly',
            'totalBookings', 'approvedBookings', 'cancelledBookings', 'avgDuration',
            'latePaymentRate', 'latePaymentMonthly', 'chronicallyLateUsers',
            'lateInvoiceDetail', 'currentOverdue',
            'totalInvoicesYear', 'paidCount', 'unpaidCount', 'lateCount',
            'totalTickets', 'resolvedTickets', 'openTickets', 'urgentTickets',
            'avgMttr', 'avgRating', 'ticketsByCategory', 'ticketMonthly', 'ticketsByPriority',
            'ticketDetail', 'urgentOpenTickets', 'ticketCategories',
            'year', 'month', 'years'
        ));
    }

    public function exportAnalytics(Request $request, string $section)
    {
        $year   = (int)($request->year  ?? now()->year);
        $month  = (int)($request->month ?? now()->month);
        $format = $request->get('format', 'excel');

        return $format === 'pdf'
            ? $this->exportPdf($section, $year, $month)
            : $this->exportExcel($section, $year, $month);
    }
    // =========================================================================
    // EXCEL HELPERS — shared style helpers
    // =========================================================================

    /** Baris header dokumen (baris 1–2, baris 3 spacer) */
    private function xlsWriteDocHeader($sh, string $lastCol, string $title, int $year): void
    {
        // Baris 1 — judul utama
        $sh->mergeCells("A1:{$lastCol}1");
        $sh->setCellValue('A1', $title);
        $sh->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 12, 'name' => 'Arial', 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '111111']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sh->getRowDimension(1)->setRowHeight(28);

        // Baris 2 — meta
        $sh->mergeCells("A2:{$lastCol}2");
        $sh->setCellValue('A2', 'Urban Kost Management System   |   Tahun: ' . $year . '   |   Dicetak: ' . now()->format('d F Y'));
        $sh->getStyle('A2')->applyFromArray([
            'font'      => ['italic' => true, 'size' => 8, 'name' => 'Arial', 'color' => ['rgb' => '444444']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sh->getRowDimension(2)->setRowHeight(14);

        // Baris 3 — spacer
        $sh->getRowDimension(3)->setRowHeight(6);
    }

    /** Section heading (strip gelap) */
    private function xlsWriteSection($sh, int $row, string $lastCol, string $text): void
    {
        $sh->mergeCells("A{$row}:{$lastCol}{$row}");
        $sh->setCellValue("A{$row}", '  ' . strtoupper($text));
        $sh->getStyle("A{$row}")->applyFromArray([
            'font'      => ['bold' => true, 'size' => 9, 'name' => 'Arial', 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '333333']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sh->getRowDimension($row)->setRowHeight(18);
    }

    /** Header kolom tabel */
    private function xlsWriteColHeader($sh, int $row, array $cols, array $headers): void
    {
        foreach ($headers as $i => $h) {
            $sh->setCellValue($cols[$i] . $row, $h);
        }
        $last = $cols[count($cols) - 1];
        $sh->getStyle("A{$row}:{$last}{$row}")->applyFromArray([
            'font'      => ['bold' => true, 'size' => 8, 'name' => 'Arial', 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '444444']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '888888']]],
        ]);
        $sh->getRowDimension($row)->setRowHeight(22);
    }

    /** Terapkan style data pada satu baris */
    private function xlsApplyDataRow($sh, int $row, string $lastCol, bool $alt = false): void
    {
        $bg = $alt ? 'F5F5F5' : 'FFFFFF';
        $sh->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
            'font'      => ['size' => 8, 'name' => 'Arial'],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bg]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'DDDDDD']]],
        ]);
        $sh->getRowDimension($row)->setRowHeight(15);
    }

    /** Baris total (strip hitam) */
    private function xlsApplyTotalRow($sh, int $row, string $lastCol): void
    {
        $sh->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
            'font'      => ['bold' => true, 'size' => 9, 'name' => 'Arial', 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '111111']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '000000']]],
        ]);
        $sh->getRowDimension($row)->setRowHeight(18);
    }

    /** Format IDR pada cell */
    private function xlsIdr($sh, string $cell): void
    {
        $sh->getStyle($cell)->getNumberFormat()->setFormatCode('#,##0');
    }

    /** Set auto-size + min width pada kolom */
    private function xlsSetColWidths($sh, array $widths): void
    {
        foreach ($widths as $col => $w) {
            $sh->getColumnDimension($col)->setWidth($w);
        }
    }

    // =========================================================================
    // EXPORT EXCEL
    // =========================================================================
    private function exportExcel(string $section, int $year, int $month): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('Urban Kost')
            ->setTitle("Laporan Urban Kost {$year}")
            ->setDescription('Diekspor ' . now()->format('d F Y'));

        switch ($section) {

            // -----------------------------------------------------------------
            // PENDAPATAN
            // Sheet 1 : KPI + Rekap Bulanan + Top 5 Kamar
            // Sheet 2 : Semua Invoice (detail arus kas)
            // Sheet 3 : Invoice Overdue Aktif
            // -----------------------------------------------------------------
            case 'pendapatan':

                /* ---------- SHEET 1 : Ringkasan + Rekap Bulanan + Top 5 ---------- */
                $sh = $spreadsheet->getActiveSheet()->setTitle('Ringkasan Pendapatan');
                $sh->getPageSetup()->setOrientation('landscape');

                $this->xlsWriteDocHeader($sh, 'H', 'LAPORAN PENDAPATAN & KEUANGAN — URBAN KOST ' . $year, $year);

                // --- KPI (baris 4–9) ---
                $this->xlsWriteSection($sh, 4, 'H', 'Ringkasan Keuangan Tahun ' . $year);

                $totalRev   = (float)Invoice::where('status', 'paid')->whereYear('period_start', $year)->sum('total_amount');
                $ovAmt      = (float)Invoice::where('status', 'overdue')->sum('total_amount');
                $paidCnt    = Invoice::where('status', 'paid')->whereYear('created_at', $year)->count();
                $ovCnt      = Invoice::where('status', 'overdue')->count();
                $unpaidCnt  = Invoice::whereIn('status', ['unpaid', 'overdue'])->whereYear('created_at', $year)->count();

                $kpiData = [
                    ['Total Pendapatan (Invoice Lunas)',   $totalRev,  'idr'],
                    ['Total Tunggakan Overdue',            $ovAmt,     'idr'],
                    ['Jumlah Invoice Lunas',               $paidCnt,   'num'],
                    ['Invoice Overdue Aktif',              $ovCnt,     'num'],
                    ['Invoice Belum Terbayar',             $unpaidCnt, 'num'],
                ];
                $kRow = 5;
                foreach ($kpiData as $idx => $kpi) {
                    $sh->setCellValue("A{$kRow}", $kpi[0]);
                    $sh->setCellValue("C{$kRow}", $kpi[1]);
                    $sh->mergeCells("A{$kRow}:B{$kRow}");
                    $sh->getStyle("A{$kRow}")->applyFromArray([
                        'font'      => ['bold' => true, 'size' => 8, 'name' => 'Arial'],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => ($idx % 2 === 0 ? 'F2F2F2' : 'FFFFFF')]],
                        'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
                        'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                    ]);
                    $sh->getStyle("C{$kRow}")->applyFromArray([
                        'font'      => ['bold' => true, 'size' => 10, 'name' => 'Arial'],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => ($idx % 2 === 0 ? 'F2F2F2' : 'FFFFFF')]],
                        'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER],
                    ]);
                    if ($kpi[2] === 'idr') $this->xlsIdr($sh, "C{$kRow}");
                    $sh->getRowDimension($kRow)->setRowHeight(18);
                    $kRow++;
                }

                // --- Rekap Bulanan (baris 11+) ---
                $this->xlsWriteSection($sh, 11, 'H', 'Rekap Pendapatan Bulanan — ' . $year);
                $bulHdr  = ['Bulan', 'Pendapatan Lunas (Rp)', 'Belum Terbayar (Rp)', 'Total Invoice', 'Invoice Lunas', 'Invoice Belum Bayar', '% Lunas', 'Kumulatif Lunas (Rp)'];
                $bulCols = ['A','B','C','D','E','F','G','H'];
                $this->xlsWriteColHeader($sh, 12, $bulCols, $bulHdr);

                $running = 0; $totInv = 0; $totLunas = 0; $totUnpaid = 0;
                for ($m = 1; $m <= 12; $m++) {
                    $r    = 12 + $m;
                    $paid = (float)Invoice::where('status', 'paid')->whereYear('period_start', $year)->whereMonth('period_start', $m)->sum('total_amount');
                    $unpd = (float)Invoice::whereIn('status', ['unpaid', 'overdue'])->whereYear('period_start', $year)->whereMonth('period_start', $m)->sum('total_amount');
                    $invT = Invoice::whereYear('period_start', $year)->whereMonth('period_start', $m)->count();
                    $invP = Invoice::where('status', 'paid')->whereYear('period_start', $year)->whereMonth('period_start', $m)->count();
                    $invU = Invoice::whereIn('status', ['unpaid', 'overdue'])->whereYear('period_start', $year)->whereMonth('period_start', $m)->count();
                    $pct  = $invT > 0 ? round(($invP / $invT) * 100, 1) : 0;
                    $running += $paid;
                    $totInv += $invT; $totLunas += $invP; $totUnpaid += $invU;

                    $sh->setCellValue("A{$r}", Carbon::create($year, $m, 1)->format('F Y'));
                    $sh->setCellValue("B{$r}", $paid);
                    $sh->setCellValue("C{$r}", $unpd);
                    $sh->setCellValue("D{$r}", $invT);
                    $sh->setCellValue("E{$r}", $invP);
                    $sh->setCellValue("F{$r}", $invU);
                    $sh->setCellValue("G{$r}", $pct . '%');
                    $sh->setCellValue("H{$r}", (float)$running);
                    $this->xlsIdr($sh, "B{$r}");
                    $this->xlsIdr($sh, "C{$r}");
                    $this->xlsIdr($sh, "H{$r}");
                    $sh->getStyle("D{$r}:F{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sh->getStyle("G{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $this->xlsApplyDataRow($sh, $r, 'H', $m % 2 === 0);
                }
                // Total bulanan
                $totRow = 25;
                $sh->setCellValue("A{$totRow}", 'TOTAL ' . $year);
                $sh->setCellValue("B{$totRow}", $totalRev);
                $sh->setCellValue("D{$totRow}", $totInv);
                $sh->setCellValue("E{$totRow}", $totLunas);
                $sh->setCellValue("F{$totRow}", $totUnpaid);
                $sh->setCellValue("H{$totRow}", $totalRev);
                $this->xlsIdr($sh, "B{$totRow}");
                $this->xlsIdr($sh, "H{$totRow}");
                $this->xlsApplyTotalRow($sh, $totRow, 'H');

                // --- Top 5 Kamar (baris 27+) ---
                $this->xlsWriteSection($sh, 27, 'H', 'Top 5 Kamar Paling Menghasilkan — ' . $year);
                $topHdr  = ['Rank', 'No. Kamar', 'Nama Kamar', 'Harga/Bulan (Rp)', 'Jml Invoice Lunas', 'Total Pendapatan (Rp)', '% Kontribusi', 'Kontribusi Top 5'];
                $topCols = ['A','B','C','D','E','F','G','H'];
                $this->xlsWriteColHeader($sh, 28, $topCols, $topHdr);

                $topRooms = Invoice::where('status', 'paid')->whereYear('period_start', $year)
                    ->select('room_id', DB::raw('SUM(total_amount) as total_revenue'), DB::raw('COUNT(*) as invoice_count'))
                    ->groupBy('room_id')->with('room:id,room_number,room_name,price')
                    ->orderByDesc('total_revenue')->limit(5)->get();

                $top5Total = $topRooms->sum('total_revenue');
                $tr = 29;
                foreach ($topRooms as $i => $room) {
                    $pctRoom   = $totalRev > 0 ? round(($room->total_revenue / $totalRev) * 100, 1) : 0;
                    $pctTop5   = $top5Total > 0 ? round(($room->total_revenue / $top5Total) * 100, 1) : 0;
                    $sh->setCellValue("A{$tr}", $i + 1);
                    $sh->setCellValue("B{$tr}", $room->room?->room_number ?? '-');
                    $sh->setCellValue("C{$tr}", $room->room?->room_name ?? '-');
                    $sh->setCellValue("D{$tr}", (float)($room->room?->price ?? 0));
                    $sh->setCellValue("E{$tr}", $room->invoice_count);
                    $sh->setCellValue("F{$tr}", (float)$room->total_revenue);
                    $sh->setCellValue("G{$tr}", $pctRoom . '%');
                    $sh->setCellValue("H{$tr}", $pctTop5 . '%');
                    $this->xlsIdr($sh, "D{$tr}");
                    $this->xlsIdr($sh, "F{$tr}");
                    foreach (['A','B','E','G','H'] as $c) $sh->getStyle("{$c}{$tr}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $this->xlsApplyDataRow($sh, $tr, 'H', $i % 2 !== 0);
                    if ($i < 3) $sh->getStyle("A{$tr}:H{$tr}")->getFont()->setBold(true);
                    $tr++;
                }
                // Baris kontribusi total top 5
                $sh->setCellValue("A{$tr}", 'Kontribusi Top 5');
                $sh->setCellValue("F{$tr}", (float)$top5Total);
                $sh->setCellValue("G{$tr}", ($totalRev > 0 ? round(($top5Total / $totalRev) * 100, 1) : 0) . '%');
                $this->xlsIdr($sh, "F{$tr}");
                $this->xlsApplyTotalRow($sh, $tr, 'H');

                $this->xlsSetColWidths($sh, ['A'=>8,'B'=>14,'C'=>24,'D'=>20,'E'=>16,'F'=>22,'G'=>14,'H'=>18]);

                /* ---------- SHEET 2 : Detail Semua Invoice ---------- */
                $sh2 = $spreadsheet->createSheet()->setTitle('Detail Invoice');
                $sh2->getPageSetup()->setOrientation('landscape');

                $this->xlsWriteDocHeader($sh2, 'I', 'DETAIL ARUS KAS — SEMUA INVOICE TAHUN ' . $year, $year);

                $invHdr  = ['No. Invoice', 'Nama Tenant', 'Email', 'No. Kamar', 'Tipe Invoice', 'Periode Tagihan', 'Total Tagihan (Rp)', 'Jatuh Tempo', 'Status', 'Tgl Dibayar'];
                $invCols = ['A','B','C','D','E','F','G','H','I','J'];
                $this->xlsWriteColHeader($sh2, 4, $invCols, $invHdr);

                $invoices = Invoice::with(['user:id,name,email', 'room:id,room_number,room_name'])
                    ->whereYear('created_at', $year)->orderByDesc('created_at')->get();
                $dRow = 5;
                foreach ($invoices as $idx => $inv) {
                    $periode = ($inv->period_start && $inv->period_end)
                        ? $inv->period_start->format('d M Y') . ' s/d ' . $inv->period_end->format('d M Y')
                        : '-';
                    $sh2->setCellValue("A{$dRow}", $inv->invoice_number);
                    $sh2->setCellValue("B{$dRow}", $inv->user?->name ?? '-');
                    $sh2->setCellValue("C{$dRow}", $inv->user?->email ?? '-');
                    $sh2->setCellValue("D{$dRow}", $inv->room?->room_number ?? '-');
                    $sh2->setCellValue("E{$dRow}", $this->invoiceTypeLabel($inv->invoice_type ?? ''));
                    $sh2->setCellValue("F{$dRow}", $periode);
                    $sh2->setCellValue("G{$dRow}", (float)$inv->total_amount);
                    $sh2->setCellValue("H{$dRow}", $inv->due_date?->format('d/m/Y') ?? '-');
                    $sh2->setCellValue("I{$dRow}", strtoupper($inv->status));
                    $sh2->setCellValue("J{$dRow}", $inv->paid_at?->format('d/m/Y') ?? '-');
                    $this->xlsIdr($sh2, "G{$dRow}");
                    foreach (['A','D','E','H','I','J'] as $c) $sh2->getStyle("{$c}{$dRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $this->xlsApplyDataRow($sh2, $dRow, 'J', $idx % 2 !== 0);
                    // Highlight overdue
                    if ($inv->status === 'overdue') {
                        $sh2->getStyle("A{$dRow}:J{$dRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFF5F5');
                        $sh2->getStyle("I{$dRow}")->getFont()->setBold(true);
                    }
                    $dRow++;
                }
                $this->xlsSetColWidths($sh2, ['A'=>22,'B'=>24,'C'=>28,'D'=>10,'E'=>16,'F'=>28,'G'=>20,'H'=>14,'I'=>14,'J'=>14]);

                /* ---------- SHEET 3 : Invoice Overdue Aktif ---------- */
                $sh3 = $spreadsheet->createSheet()->setTitle('Invoice Overdue Aktif');
                $sh3->getPageSetup()->setOrientation('landscape');

                $this->xlsWriteDocHeader($sh3, 'G', 'INVOICE OVERDUE AKTIF — PERLU TINDAKAN SEGERA', $year);

                $ovHdr  = ['No. Invoice', 'Nama Tenant', 'Email', 'No. Kamar', 'Total Tagihan (Rp)', 'Jatuh Tempo', 'Hari Terlambat'];
                $ovCols = ['A','B','C','D','E','F','G'];
                $this->xlsWriteColHeader($sh3, 4, $ovCols, $ovHdr);

                $overdueList = Invoice::with(['user:id,name,email', 'room:id,room_number'])
                    ->where('status', 'overdue')->orderByDesc('due_date')->get();
                $ovRow = 5;
                foreach ($overdueList as $idx => $od) {
                    $days = $od->due_date ? (int)now()->diffInDays($od->due_date) : 0;
                    $sh3->setCellValue("A{$ovRow}", $od->invoice_number);
                    $sh3->setCellValue("B{$ovRow}", $od->user?->name ?? '-');
                    $sh3->setCellValue("C{$ovRow}", $od->user?->email ?? '-');
                    $sh3->setCellValue("D{$ovRow}", $od->room?->room_number ?? '-');
                    $sh3->setCellValue("E{$ovRow}", (float)$od->total_amount);
                    $sh3->setCellValue("F{$ovRow}", $od->due_date?->format('d/m/Y') ?? '-');
                    $sh3->setCellValue("G{$ovRow}", $days . ' hari');
                    $this->xlsIdr($sh3, "E{$ovRow}");
                    foreach (['A','D','F','G'] as $c) $sh3->getStyle("{$c}{$ovRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $this->xlsApplyDataRow($sh3, $ovRow, 'G', $idx % 2 !== 0);
                    $sh3->getStyle("E{$ovRow}:G{$ovRow}")->getFont()->setBold(true);
                    $ovRow++;
                }
                if ($overdueList->isEmpty()) {
                    $sh3->mergeCells('A5:G5');
                    $sh3->setCellValue('A5', 'Tidak ada invoice overdue aktif saat ini.');
                    $sh3->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sh3->getStyle('A5')->getFont()->setItalic(true);
                }
                $this->xlsSetColWidths($sh3, ['A'=>22,'B'=>24,'C'=>28,'D'=>10,'E'=>22,'F'=>14,'G'=>14]);

                $spreadsheet->setActiveSheetIndex(0);
                $filename = "urban-kost-laporan-pendapatan-{$year}.xlsx";
                break;

            // -----------------------------------------------------------------
            // BOOKING
            // Sheet 1 : KPI + Tren Bulanan
            // Sheet 2 : Statistik Semua Kamar
            // Sheet 3 : Kamar Jarang Dibooking + Riwayat Detail Booking
            // -----------------------------------------------------------------
            case 'booking':

                $totalBk  = Booking::whereYear('created_at', $year)->count();
                $appBk    = Booking::whereIn('status', ['approved', 'active', 'completed'])->whereYear('created_at', $year)->count();
                $canBk    = Booking::whereIn('status', ['cancelled', 'rejected'])->whereYear('created_at', $year)->count();
                $pendBk   = Booking::whereIn('status', ['pending', 'under_review'])->whereYear('created_at', $year)->count();
                $avgDurBk = round(Booking::whereYear('created_at', $year)->avg('duration_months') ?? 0, 1);
                $appRate  = $totalBk > 0 ? round(($appBk / $totalBk) * 100, 1) : 0;

                /* ---------- SHEET 1 : KPI + Tren Bulanan ---------- */
                $sh = $spreadsheet->getActiveSheet()->setTitle('KPI & Tren Bulanan');
                $sh->getPageSetup()->setOrientation('landscape');

                $this->xlsWriteDocHeader($sh, 'F', 'LAPORAN RIWAYAT & STATISTIK BOOKING — URBAN KOST ' . $year, $year);

                // KPI
                $this->xlsWriteSection($sh, 4, 'F', 'Ringkasan Booking Tahun ' . $year);
                $bkKpi = [
                    ['Total Booking',               $totalBk,              'num'],
                    ['Disetujui / Aktif / Selesai', $appBk . ' (' . $appRate . '%)', 'txt'],
                    ['Dibatalkan / Ditolak',         $canBk,                'num'],
                    ['Pending / Dalam Tinjauan',     $pendBk,               'num'],
                    ['Rata-rata Durasi Sewa',        $avgDurBk . ' bulan',  'txt'],
                    ['Approval Rate',                $appRate . '%',        'txt'],
                ];
                $kRow = 5;
                foreach ($bkKpi as $idx => $kpi) {
                    $sh->setCellValue("A{$kRow}", $kpi[0]);
                    $sh->setCellValue("C{$kRow}", $kpi[1]);
                    $sh->mergeCells("A{$kRow}:B{$kRow}");
                    $bg = $idx % 2 === 0 ? 'F2F2F2' : 'FFFFFF';
                    $sh->getStyle("A{$kRow}")->applyFromArray(['font' => ['bold' => true, 'size' => 8, 'name' => 'Arial'], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bg]], 'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]], 'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]]);
                    $sh->getStyle("C{$kRow}")->applyFromArray(['font' => ['bold' => true, 'size' => 10, 'name' => 'Arial'], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bg]], 'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER]]);
                    $sh->getRowDimension($kRow)->setRowHeight(18);
                    $kRow++;
                }

                // Tren Bulanan
                $this->xlsWriteSection($sh, 12, 'F', 'Tren Booking Bulanan — ' . $year);
                $trendHdr  = ['Bulan', 'Total Booking', 'Disetujui / Aktif', 'Dibatalkan / Ditolak', 'Pending / Review', '% Approval Rate'];
                $trendCols = ['A','B','C','D','E','F'];
                $this->xlsWriteColHeader($sh, 13, $trendCols, $trendHdr);

                $totTot = $totApp = $totCan = $totPend = 0;
                for ($m = 1; $m <= 12; $m++) {
                    $r    = 13 + $m;
                    $tot  = Booking::whereYear('created_at', $year)->whereMonth('created_at', $m)->count();
                    $app  = Booking::whereIn('status', ['approved','active','completed'])->whereYear('created_at', $year)->whereMonth('created_at', $m)->count();
                    $can  = Booking::whereIn('status', ['cancelled','rejected'])->whereYear('created_at', $year)->whereMonth('created_at', $m)->count();
                    $pend = Booking::whereIn('status', ['pending','under_review'])->whereYear('created_at', $year)->whereMonth('created_at', $m)->count();
                    $ap   = $tot > 0 ? round(($app / $tot) * 100, 1) : 0;
                    $totTot += $tot; $totApp += $app; $totCan += $can; $totPend += $pend;

                    $sh->setCellValue("A{$r}", Carbon::create($year, $m, 1)->format('F Y'));
                    $sh->setCellValue("B{$r}", $tot);
                    $sh->setCellValue("C{$r}", $app);
                    $sh->setCellValue("D{$r}", $can);
                    $sh->setCellValue("E{$r}", $pend);
                    $sh->setCellValue("F{$r}", $ap . '%');
                    foreach (['B','C','D','E','F'] as $c) $sh->getStyle("{$c}{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $this->xlsApplyDataRow($sh, $r, 'F', $m % 2 === 0);
                }
                $totRowBk = 26;
                $sh->setCellValue("A{$totRowBk}", 'TOTAL ' . $year);
                $sh->setCellValue("B{$totRowBk}", $totTot);
                $sh->setCellValue("C{$totRowBk}", $totApp);
                $sh->setCellValue("D{$totRowBk}", $totCan);
                $sh->setCellValue("E{$totRowBk}", $totPend);
                $this->xlsApplyTotalRow($sh, $totRowBk, 'F');

                $this->xlsSetColWidths($sh, ['A'=>22,'B'=>14,'C'=>18,'D'=>20,'E'=>18,'F'=>16]);

                /* ---------- SHEET 2 : Statistik Semua Kamar ---------- */
                $sh2 = $spreadsheet->createSheet()->setTitle('Statistik Semua Kamar');
                $sh2->getPageSetup()->setOrientation('landscape');

                $this->xlsWriteDocHeader($sh2, 'J', 'STATISTIK BOOKING PER KAMAR — ' . $year, $year);

                $bkStatHdr  = ['No. Kamar', 'Nama Kamar', 'Status Kamar', 'Harga/Bulan (Rp)', 'Total Booking', 'Disetujui', 'Dibatalkan', 'Avg Durasi (Bln)', 'Total Pendapatan (Rp)', 'Tenant Aktif'];
                $bkStatCols = ['A','B','C','D','E','F','G','H','I','J'];
                $this->xlsWriteColHeader($sh2, 4, $bkStatCols, $bkStatHdr);

                $allRooms = Room::orderBy('room_number')->get();
                $bsRow = 5;
                foreach ($allRooms as $idx => $room) {
                    $bkgs     = Booking::where('room_id', $room->id)->whereYear('created_at', $year)->get();
                    $revenue  = (float)Invoice::where('status', 'paid')->where('room_id', $room->id)->whereYear('period_start', $year)->sum('total_amount');
                    $activeBk = Booking::where('room_id', $room->id)->whereIn('status', ['active','approved'])->with('user:id,name')->first();
                    $stLabel  = match ($room->status) {'occupied'=>'Terisi','available'=>'Kosong','reserved'=>'Dipesan','maintenance'=>'Perbaikan',default=>$room->status};

                    $sh2->setCellValue("A{$bsRow}", $room->room_number);
                    $sh2->setCellValue("B{$bsRow}", $room->room_name ?? '-');
                    $sh2->setCellValue("C{$bsRow}", $stLabel);
                    $sh2->setCellValue("D{$bsRow}", (float)$room->price);
                    $sh2->setCellValue("E{$bsRow}", $bkgs->count());
                    $sh2->setCellValue("F{$bsRow}", $bkgs->whereIn('status', ['approved','active','completed'])->count());
                    $sh2->setCellValue("G{$bsRow}", $bkgs->whereIn('status', ['cancelled','rejected'])->count());
                    $sh2->setCellValue("H{$bsRow}", round($bkgs->avg('duration_months') ?? 0, 1));
                    $sh2->setCellValue("I{$bsRow}", $revenue);
                    $sh2->setCellValue("J{$bsRow}", $activeBk?->user?->name ?? '-');
                    $this->xlsIdr($sh2, "D{$bsRow}");
                    $this->xlsIdr($sh2, "I{$bsRow}");
                    foreach (['A','C','E','F','G','H'] as $c) $sh2->getStyle("{$c}{$bsRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $this->xlsApplyDataRow($sh2, $bsRow, 'J', $idx % 2 !== 0);
                    // Merah untuk 0 booking
                    if ($bkgs->count() === 0) {
                        $sh2->getStyle("A{$bsRow}:J{$bsRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFF5F5');
                        $sh2->getStyle("E{$bsRow}")->getFont()->setBold(true)->getColor()->setRGB('CC0000');
                    }
                    $bsRow++;
                }
                $this->xlsSetColWidths($sh2, ['A'=>12,'B'=>22,'C'=>14,'D'=>18,'E'=>13,'F'=>13,'G'=>13,'H'=>16,'I'=>22,'J'=>22]);

                /* ---------- SHEET 3 : Kamar Jarang + Riwayat Detail ---------- */
                $sh3 = $spreadsheet->createSheet()->setTitle('Riwayat Detail Booking');
                $sh3->getPageSetup()->setOrientation('landscape');

                $this->xlsWriteDocHeader($sh3, 'I', 'RIWAYAT DETAIL BOOKING — ' . $year, $year);

                // Kamar Jarang
                $this->xlsWriteSection($sh3, 4, 'I', 'Kamar Paling Jarang Dibooking (Perlu Perhatian)');
                $lbHdr  = ['No. Kamar', 'Nama Kamar', 'Status', 'Harga/Bulan (Rp)', 'Total Booking', 'Hari Kosong (Est.)'];
                $lbCols = ['A','B','C','D','E','F'];
                $this->xlsWriteColHeader($sh3, 5, $lbCols, $lbHdr);

                $leastBooked = $allRooms->map(function ($room) use ($year) {
                    $bkgs = Booking::where('room_id', $room->id)->whereYear('created_at', $year)->get();
                    return [
                        'room'           => $room,
                        'total_bookings' => $bkgs->count(),
                        'days_vacant'    => $room->status === 'available'
                            ? (int)(optional($bkgs->sortByDesc('updated_at')->first()?->updated_at ?? now())->diffInDays(now()))
                            : 0,
                    ];
                })->sortBy('total_bookings')->take(5);

                $lbRow = 6;
                foreach ($leastBooked as $idx => $lb) {
                    $sh3->setCellValue("A{$lbRow}", $lb['room']->room_number);
                    $sh3->setCellValue("B{$lbRow}", $lb['room']->room_name ?? '-');
                    $sh3->setCellValue("C{$lbRow}", $lb['room']->status);
                    $sh3->setCellValue("D{$lbRow}", (float)$lb['room']->price);
                    $sh3->setCellValue("E{$lbRow}", $lb['total_bookings']);
                    $sh3->setCellValue("F{$lbRow}", $lb['days_vacant'] > 0 ? '~' . $lb['days_vacant'] . ' hari' : '-');
                    $this->xlsIdr($sh3, "D{$lbRow}");
                    foreach (['A','C','E','F'] as $c) $sh3->getStyle("{$c}{$lbRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $this->xlsApplyDataRow($sh3, $lbRow, 'F', $idx % 2 !== 0);
                    if ($lb['total_bookings'] === 0) $sh3->getStyle("A{$lbRow}:F{$lbRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFF5F5');
                    $lbRow++;
                }

                // Detail Booking
                $this->xlsWriteSection($sh3, $lbRow + 1, 'I', 'Riwayat Detail Semua Booking — ' . $year);
                $bkDHdr  = ['Kode Booking', 'Nama Tenant', 'Email', 'No. HP', 'No. Kamar', 'Tipe Booking', 'Check-in', 'Durasi (Bln)', 'Status', 'Catatan'];
                $bkDCols = ['A','B','C','D','E','F','G','H','I','J'];
                // Re-map kolom — kita punya 10 kolom, header dokumen sampai I, perlu extend ke J
                $this->xlsWriteColHeader($sh3, $lbRow + 2, $bkDCols, $bkDHdr);

                $bookings = Booking::with(['user:id,name,email,phone', 'room:id,room_number'])
                    ->whereYear('created_at', $year)->orderByDesc('created_at')->get();
                $bkDRow = $lbRow + 3;
                foreach ($bookings as $idx => $bk) {
                    $stLbl = match ($bk->status) {'pending'=>'Pending','under_review'=>'Ditinjau','approved'=>'Disetujui','active'=>'Aktif','completed'=>'Selesai','cancelled'=>'Dibatalkan','rejected'=>'Ditolak','waiting_list'=>'Waiting List',default=>$bk->status};
                    $sh3->setCellValue("A{$bkDRow}", $bk->booking_code);
                    $sh3->setCellValue("B{$bkDRow}", $bk->user?->name ?? $bk->full_name ?? '-');
                    $sh3->setCellValue("C{$bkDRow}", $bk->user?->email ?? '-');
                    $sh3->setCellValue("D{$bkDRow}", $bk->user?->phone ?? '-');
                    $sh3->setCellValue("E{$bkDRow}", $bk->room?->room_number ?? '-');
                    $sh3->setCellValue("F{$bkDRow}", ucfirst($bk->booking_type ?? 'regular'));
                    $sh3->setCellValue("G{$bkDRow}", $bk->check_in_date?->format('d/m/Y') ?? '-');
                    $sh3->setCellValue("H{$bkDRow}", $bk->duration_months ?? '-');
                    $sh3->setCellValue("I{$bkDRow}", $stLbl);
                    // Batasi panjang catatan agar tidak terlalu panjang, bungkus ke bawah
                    $catatan = \Str::limit($bk->admin_notes ?? $bk->cancellation_reason ?? '-', 120);
                    $sh3->setCellValue("J{$bkDRow}", $catatan);
                    foreach (['A','E','F','G','H','I'] as $c) $sh3->getStyle("{$c}{$bkDRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $this->xlsApplyDataRow($sh3, $bkDRow, 'J', $idx % 2 !== 0);
                    // wrapText pada kolom Catatan agar tidak tembus ke kanan
                    $sh3->getStyle("J{$bkDRow}")->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_TOP);
                    $sh3->getRowDimension($bkDRow)->setRowHeight(-1); // auto-height
                    if (in_array($bk->status, ['cancelled','rejected'])) $sh3->getStyle("A{$bkDRow}:J{$bkDRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8F8F8');
                    $bkDRow++;
                }
                // Kolom J lebih sempit agar wrap ke bawah, bukan ke kanan
                $this->xlsSetColWidths($sh3, ['A'=>16,'B'=>20,'C'=>24,'D'=>14,'E'=>9,'F'=>12,'G'=>11,'H'=>10,'I'=>12,'J'=>28]);
                $sh3->getColumnDimension('J')->setAutoSize(false); // paksa fixed width agar wrapText aktif

                $spreadsheet->setActiveSheetIndex(0);
                $filename = "urban-kost-laporan-booking-{$year}.xlsx";
                break;

            // -----------------------------------------------------------------
            // KETERLAMBATAN
            // Sheet 1 : KPI + Rekap Bulanan
            // Sheet 2 : Invoice Overdue Aktif
            // Sheet 3 : Detail Invoice Terlambat + Tenant Sering Terlambat
            // -----------------------------------------------------------------
            case 'keterlambatan':

                $paidCntLt = Invoice::where('status', 'paid')->whereYear('created_at', $year)->count();
                $lateCntLt = Invoice::where('status', 'paid')->whereYear('created_at', $year)->whereRaw('paid_at > due_date')->count();
                $overNow   = Invoice::where('status', 'overdue')->count();
                $overAmt   = (float)Invoice::where('status', 'overdue')->sum('total_amount');
                $lateRate  = $paidCntLt > 0 ? round(($lateCntLt / $paidCntLt) * 100, 1) : 0;
                $avgLate   = round(Invoice::where('status','paid')->whereYear('created_at',$year)->whereRaw('paid_at > due_date')->avg(DB::raw('DATEDIFF(paid_at, due_date)')) ?? 0, 1);

                /* ---------- SHEET 1 : KPI + Rekap Bulanan ---------- */
                $sh = $spreadsheet->getActiveSheet()->setTitle('Ringkasan Keterlambatan');
                $sh->getPageSetup()->setOrientation('landscape');

                $this->xlsWriteDocHeader($sh, 'G', 'LAPORAN PEMBAYARAN & KETERLAMBATAN — URBAN KOST ' . $year, $year);

                // KPI
                $this->xlsWriteSection($sh, 4, 'G', 'Ringkasan Keterlambatan Tahun ' . $year);
                $ltKpi = [
                    ['Invoice Lunas Tahun ' . $year,           $paidCntLt,             'num'],
                    ['Dibayar Terlambat (setelah jatuh tempo)', $lateCntLt,             'num'],
                    ['Tingkat Keterlambatan',                   $lateRate . '%',        'txt'],
                    ['Overdue Aktif Saat Ini',                  $overNow,               'num'],
                    ['Total Nilai Tunggakan Overdue (Rp)',      $overAmt,               'idr'],
                    ['Rata-rata Hari Terlambat',                $avgLate . ' hari',     'txt'],
                ];
                $kRow = 5;
                foreach ($ltKpi as $idx => $kpi) {
                    $sh->setCellValue("A{$kRow}", $kpi[0]);
                    $sh->setCellValue("C{$kRow}", $kpi[1]);
                    $sh->mergeCells("A{$kRow}:B{$kRow}");
                    $bg = $idx % 2 === 0 ? 'F2F2F2' : 'FFFFFF';
                    $sh->getStyle("A{$kRow}")->applyFromArray(['font'=>['bold'=>true,'size'=>8,'name'=>'Arial'],'fill'=>['fillType'=>Fill::FILL_SOLID,'startColor'=>['rgb'=>$bg]],'borders'=>['allBorders'=>['borderStyle'=>Border::BORDER_THIN,'color'=>['rgb'=>'CCCCCC']]],'alignment'=>['vertical'=>Alignment::VERTICAL_CENTER]]);
                    $sh->getStyle("C{$kRow}")->applyFromArray(['font'=>['bold'=>true,'size'=>10,'name'=>'Arial'],'fill'=>['fillType'=>Fill::FILL_SOLID,'startColor'=>['rgb'=>$bg]],'borders'=>['allBorders'=>['borderStyle'=>Border::BORDER_THIN,'color'=>['rgb'=>'CCCCCC']]],'alignment'=>['horizontal'=>Alignment::HORIZONTAL_RIGHT,'vertical'=>Alignment::VERTICAL_CENTER]]);
                    if ($kpi[2]==='idr') $this->xlsIdr($sh, "C{$kRow}");
                    $sh->getRowDimension($kRow)->setRowHeight(18);
                    $kRow++;
                }

                // Rekap Bulanan
                $this->xlsWriteSection($sh, 12, 'G', 'Rekap Bulanan Keterlambatan — ' . $year);
                $ltBulHdr  = ['Bulan', 'Invoice Terlambat', 'Avg Hari Terlambat', 'Max Hari Terlambat', 'Invoice Lunas', 'Invoice Overdue', '% Keterlambatan'];
                $ltBulCols = ['A','B','C','D','E','F','G'];
                $this->xlsWriteColHeader($sh, 13, $ltBulCols, $ltBulHdr);

                $lateData = Invoice::where('status', 'paid')->whereYear('period_start', $year)->whereRaw('paid_at > due_date')
                    ->select(DB::raw('MONTH(period_start) as m'), DB::raw('COUNT(*) as cnt'), DB::raw('AVG(DATEDIFF(paid_at, due_date)) as avg_d'), DB::raw('MAX(DATEDIFF(paid_at, due_date)) as max_d'))
                    ->groupBy('m')->get()->keyBy('m');

                for ($m = 1; $m <= 12; $m++) {
                    $r     = 13 + $m;
                    $cnt   = $lateData->get($m)?->cnt ?? 0;
                    $avg   = round($lateData->get($m)?->avg_d ?? 0, 1);
                    $max   = $lateData->get($m)?->max_d ?? 0;
                    $lunas = Invoice::where('status', 'paid')->whereYear('period_start', $year)->whereMonth('period_start', $m)->count();
                    $ov    = Invoice::where('status', 'overdue')->whereYear('period_start', $year)->whereMonth('period_start', $m)->count();
                    $pctLt = $lunas > 0 ? round(($cnt / $lunas) * 100, 1) : 0;

                    $sh->setCellValue("A{$r}", Carbon::create($year, $m, 1)->format('F Y'));
                    $sh->setCellValue("B{$r}", $cnt);
                    $sh->setCellValue("C{$r}", $avg . ' hari');
                    $sh->setCellValue("D{$r}", $max . ' hari');
                    $sh->setCellValue("E{$r}", $lunas);
                    $sh->setCellValue("F{$r}", $ov);
                    $sh->setCellValue("G{$r}", $pctLt . '%');
                    foreach (['B','C','D','E','F','G'] as $c) $sh->getStyle("{$c}{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $this->xlsApplyDataRow($sh, $r, 'G', $m % 2 === 0);
                    // Highlight kritis
                    if ($avg > 14) $sh->getStyle("C{$r}")->getFont()->setBold(true)->getColor()->setRGB('CC0000');
                    elseif ($avg > 7) $sh->getStyle("C{$r}")->getFont()->setBold(true)->getColor()->setRGB('AA6600');
                }
                $this->xlsSetColWidths($sh, ['A'=>22,'B'=>18,'C'=>20,'D'=>20,'E'=>16,'F'=>16,'G'=>18]);

                /* ---------- SHEET 2 : Invoice Overdue Aktif ---------- */
                $sh2 = $spreadsheet->createSheet()->setTitle('Invoice Overdue Aktif');
                $sh2->getPageSetup()->setOrientation('landscape');

                $this->xlsWriteDocHeader($sh2, 'G', 'INVOICE OVERDUE AKTIF — PERLU TINDAKAN SEGERA (per ' . now()->format('d F Y') . ')', $year);

                $ovHdr2  = ['No. Invoice', 'Nama Tenant', 'Email', 'No. Kamar', 'Total Tagihan (Rp)', 'Jatuh Tempo', 'Hari Terlambat'];
                $ovCols2 = ['A','B','C','D','E','F','G'];
                $this->xlsWriteColHeader($sh2, 4, $ovCols2, $ovHdr2);

                $overdueList2 = Invoice::with(['user:id,name,email','room:id,room_number'])->where('status','overdue')->orderByDesc('due_date')->get();
                $ovRow2 = 5;
                foreach ($overdueList2 as $idx => $od) {
                    $days = $od->due_date ? (int)now()->diffInDays($od->due_date) : 0;
                    $sh2->setCellValue("A{$ovRow2}", $od->invoice_number);
                    $sh2->setCellValue("B{$ovRow2}", $od->user?->name ?? '-');
                    $sh2->setCellValue("C{$ovRow2}", $od->user?->email ?? '-');
                    $sh2->setCellValue("D{$ovRow2}", $od->room?->room_number ?? '-');
                    $sh2->setCellValue("E{$ovRow2}", (float)$od->total_amount);
                    $sh2->setCellValue("F{$ovRow2}", $od->due_date?->format('d/m/Y') ?? '-');
                    $sh2->setCellValue("G{$ovRow2}", $days . ' hari');
                    $this->xlsIdr($sh2, "E{$ovRow2}");
                    foreach (['A','D','F','G'] as $c) $sh2->getStyle("{$c}{$ovRow2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $this->xlsApplyDataRow($sh2, $ovRow2, 'G', $idx % 2 !== 0);
                    $sh2->getStyle("E{$ovRow2}:G{$ovRow2}")->getFont()->setBold(true);
                    $ovRow2++;
                }
                if ($overdueList2->isEmpty()) {
                    $sh2->mergeCells('A5:G5');
                    $sh2->setCellValue('A5', 'Tidak ada invoice overdue aktif saat ini.');
                    $sh2->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sh2->getStyle('A5')->getFont()->setItalic(true);
                }
                $this->xlsSetColWidths($sh2, ['A'=>22,'B'=>24,'C'=>28,'D'=>10,'E'=>22,'F'=>14,'G'=>14]);

                /* ---------- SHEET 3 : Detail Terlambat + Tenant Sering Terlambat ---------- */
                $sh3 = $spreadsheet->createSheet()->setTitle('Detail & Tenant Terlambat');
                $sh3->getPageSetup()->setOrientation('landscape');

                $this->xlsWriteDocHeader($sh3, 'I', 'DETAIL INVOICE TERLAMBAT & TENANT SERING TERLAMBAT — ' . $year, $year);

                // Tenant Sering Terlambat
                $this->xlsWriteSection($sh3, 4, 'I', 'Tenant Paling Sering Terlambat (≥2x) — ' . $year);
                $chHdr  = ['No.', 'Nama Tenant', 'Email', 'No. HP', 'Jml Terlambat', 'Avg Hari Terlambat', 'Total Sudah Dibayar (Rp)'];
                $chCols = ['A','B','C','D','E','F','G'];
                $this->xlsWriteColHeader($sh3, 5, $chCols, $chHdr);

                $chronLate = Invoice::where('status','paid')->whereYear('period_start',$year)->whereRaw('paid_at > due_date')
                    ->select('user_id', DB::raw('COUNT(*) as late_count'), DB::raw('AVG(DATEDIFF(paid_at, due_date)) as avg_days'), DB::raw('SUM(total_amount) as total_paid'))
                    ->groupBy('user_id')->having('late_count','>=',2)->with('user:id,name,email,phone')
                    ->orderByDesc('late_count')->get();

                $chr = 6;
                foreach ($chronLate as $idx => $cl) {
                    $sh3->setCellValue("A{$chr}", $idx + 1);
                    $sh3->setCellValue("B{$chr}", $cl->user?->name ?? '-');
                    $sh3->setCellValue("C{$chr}", $cl->user?->email ?? '-');
                    $sh3->setCellValue("D{$chr}", $cl->user?->phone ?? '-');
                    $sh3->setCellValue("E{$chr}", $cl->late_count . 'x');
                    $sh3->setCellValue("F{$chr}", round($cl->avg_days, 1) . ' hari');
                    $sh3->setCellValue("G{$chr}", (float)$cl->total_paid);
                    $this->xlsIdr($sh3, "G{$chr}");
                    foreach (['A','E','F'] as $c) $sh3->getStyle("{$c}{$chr}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $this->xlsApplyDataRow($sh3, $chr, 'G', $idx % 2 !== 0);
                    $sh3->getStyle("E{$chr}")->getFont()->setBold(true);
                    $chr++;
                }
                if ($chronLate->isEmpty()) {
                    $sh3->mergeCells('A6:G6');
                    $sh3->setCellValue('A6', 'Tidak ada tenant yang tercatat sering terlambat (≥2x) pada tahun ' . $year);
                    $sh3->getStyle('A6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sh3->getStyle('A6')->getFont()->setItalic(true);
                    $chr = 7;
                }

                // Detail Invoice Terlambat
                $this->xlsWriteSection($sh3, $chr + 1, 'I', 'Detail Invoice yang Dibayar Terlambat — ' . $year . ' (urut: paling lama)');
                $ltDHdr  = ['No. Invoice', 'Nama Tenant', 'Email', 'No. Kamar', 'Total Tagihan (Rp)', 'Jatuh Tempo', 'Tgl Dibayar', 'Hari Terlambat', 'Kategori'];
                $ltDCols = ['A','B','C','D','E','F','G','H','I'];
                $this->xlsWriteColHeader($sh3, $chr + 2, $ltDCols, $ltDHdr);

                $lateInvs = Invoice::with(['user:id,name,email','room:id,room_number'])
                    ->where('status','paid')->whereYear('period_start',$year)->whereRaw('paid_at > due_date')
                    ->orderByDesc(DB::raw('DATEDIFF(paid_at, due_date)'))->get();

                $ldr = $chr + 3;
                foreach ($lateInvs as $idx => $li) {
                    $dl  = $li->paid_at && $li->due_date ? (int)$li->paid_at->diffInDays($li->due_date) : 0;
                    $cat = $dl > 14 ? 'Kritis (>14 hari)' : ($dl > 7 ? 'Sedang (8-14 hari)' : 'Ringan (≤7 hari)');
                    $sh3->setCellValue("A{$ldr}", $li->invoice_number);
                    $sh3->setCellValue("B{$ldr}", $li->user?->name ?? '-');
                    $sh3->setCellValue("C{$ldr}", $li->user?->email ?? '-');
                    $sh3->setCellValue("D{$ldr}", $li->room?->room_number ?? '-');
                    $sh3->setCellValue("E{$ldr}", (float)$li->total_amount);
                    $sh3->setCellValue("F{$ldr}", $li->due_date?->format('d/m/Y') ?? '-');
                    $sh3->setCellValue("G{$ldr}", $li->paid_at?->format('d/m/Y') ?? '-');
                    $sh3->setCellValue("H{$ldr}", $dl . ' hari');
                    $sh3->setCellValue("I{$ldr}", $cat);
                    $this->xlsIdr($sh3, "E{$ldr}");
                    foreach (['A','D','F','G','H'] as $c) $sh3->getStyle("{$c}{$ldr}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $this->xlsApplyDataRow($sh3, $ldr, 'I', $idx % 2 !== 0);
                    if ($dl > 14) { $sh3->getStyle("H{$ldr}:I{$ldr}")->getFont()->setBold(true); $sh3->getStyle("H{$ldr}:I{$ldr}")->getFont()->getColor()->setRGB('CC0000'); }
                    elseif ($dl > 7) $sh3->getStyle("H{$ldr}:I{$ldr}")->getFont()->setBold(true);
                    $ldr++;
                }
                $this->xlsSetColWidths($sh3, ['A'=>22,'B'=>24,'C'=>28,'D'=>10,'E'=>22,'F'=>14,'G'=>14,'H'=>14,'I'=>22]);

                $spreadsheet->setActiveSheetIndex(0);
                $filename = "urban-kost-laporan-keterlambatan-{$year}.xlsx";
                break;

            // -----------------------------------------------------------------
            // TIKET
            // Sheet 1 : KPI + Rekap Per Kategori + Tren Bulanan
            // Sheet 2 : Tiket Urgent Masih Terbuka
            // Sheet 3 : Detail Semua Tiket
            // -----------------------------------------------------------------
            case 'tiket':

                $totalTk    = Ticket::whereYear('created_at', $year)->count();
                $resolvedTk = Ticket::where('status','resolved')->whereYear('created_at',$year)->count();
                $openTk     = Ticket::whereIn('status',['open','in_progress'])->count();
                $urgentTk   = Ticket::where('priority','urgent')->whereIn('status',['open','in_progress'])->count();
                $avgMttrTk  = $this->calcAvgMttr($year);
                $avgRatTk   = round(Ticket::whereYear('created_at',$year)->whereNotNull('rating')->avg('rating') ?? 0, 1);
                $resoRate   = $totalTk > 0 ? round(($resolvedTk / $totalTk) * 100, 1) : 0;

                /* ---------- SHEET 1 : KPI + Per Kategori + Tren ---------- */
                $sh = $spreadsheet->getActiveSheet()->setTitle('Ringkasan & Rekap Tiket');
                $sh->getPageSetup()->setOrientation('landscape');

                $this->xlsWriteDocHeader($sh, 'H', 'LAPORAN KELUHAN & PERFORMA TIKET LAYANAN — URBAN KOST ' . $year, $year);

                // KPI
                $this->xlsWriteSection($sh, 4, 'H', 'Ringkasan Performa Tiket ' . $year);
                $tkKpi = [
                    ['Total Tiket Masuk Tahun ' . $year,     $totalTk,               'num'],
                    ['Tiket Terselesaikan',                   $resolvedTk,            'num'],
                    ['Tingkat Penyelesaian',                  $resoRate . '%',        'txt'],
                    ['Masih Terbuka (open/in_progress)',      $openTk,                'num'],
                    ['Tiket Urgent Belum Selesai',            $urgentTk,              'num'],
                    ['Avg MTTR (Mean Time To Resolve)',       $avgMttrTk . ' jam',    'txt'],
                    ['Target MTTR',                           '< 24 jam',             'txt'],
                    ['Rata-rata Rating Kepuasan',             $avgRatTk . ' / 5',     'txt'],
                ];
                $kRow = 5;
                foreach ($tkKpi as $idx => $kpi) {
                    $sh->setCellValue("A{$kRow}", $kpi[0]);
                    $sh->setCellValue("C{$kRow}", $kpi[1]);
                    $sh->mergeCells("A{$kRow}:B{$kRow}");
                    $bg = $idx % 2 === 0 ? 'F2F2F2' : 'FFFFFF';
                    $sh->getStyle("A{$kRow}")->applyFromArray(['font'=>['bold'=>true,'size'=>8,'name'=>'Arial'],'fill'=>['fillType'=>Fill::FILL_SOLID,'startColor'=>['rgb'=>$bg]],'borders'=>['allBorders'=>['borderStyle'=>Border::BORDER_THIN,'color'=>['rgb'=>'CCCCCC']]],'alignment'=>['vertical'=>Alignment::VERTICAL_CENTER]]);
                    $sh->getStyle("C{$kRow}")->applyFromArray(['font'=>['bold'=>true,'size'=>10,'name'=>'Arial'],'fill'=>['fillType'=>Fill::FILL_SOLID,'startColor'=>['rgb'=>$bg]],'borders'=>['allBorders'=>['borderStyle'=>Border::BORDER_THIN,'color'=>['rgb'=>'CCCCCC']]],'alignment'=>['horizontal'=>Alignment::HORIZONTAL_RIGHT,'vertical'=>Alignment::VERTICAL_CENTER]]);
                    $sh->getRowDimension($kRow)->setRowHeight(18);
                    $kRow++;
                }

                // Per Kategori
                $this->xlsWriteSection($sh, 14, 'H', 'Rekap Per Kategori & Prioritas — ' . $year);
                $catHdr  = ['Kategori', 'Total Tiket', 'Terselesaikan', '% Selesai', 'Masih Terbuka', 'Tiket Urgent', 'Avg Rating', 'Kinerja'];
                $catCols = ['A','B','C','D','E','F','G','H'];
                $this->xlsWriteColHeader($sh, 15, $catCols, $catHdr);

                $catData = Ticket::selectRaw('category, COUNT(*) as count, AVG(rating) as avg_rating, SUM(CASE WHEN status="resolved" THEN 1 ELSE 0 END) as resolved_count, SUM(CASE WHEN priority="urgent" THEN 1 ELSE 0 END) as urgent_count, SUM(CASE WHEN status IN ("open","in_progress") THEN 1 ELSE 0 END) as open_count')
                    ->whereYear('created_at', $year)->groupBy('category')->get();

                $cdr = 16;
                foreach ($catData as $idx => $cd) {
                    $pctCat  = $cd->count > 0 ? round(($cd->resolved_count / $cd->count) * 100, 1) : 0;
                    $perfCat = $pctCat >= 80 ? 'Baik' : ($pctCat >= 60 ? 'Perlu Perhatian' : 'Perlu Perbaikan');
                    $sh->setCellValue("A{$cdr}", ucfirst($cd->category));
                    $sh->setCellValue("B{$cdr}", $cd->count);
                    $sh->setCellValue("C{$cdr}", $cd->resolved_count);
                    $sh->setCellValue("D{$cdr}", $pctCat . '%');
                    $sh->setCellValue("E{$cdr}", $cd->open_count);
                    $sh->setCellValue("F{$cdr}", $cd->urgent_count);
                    $sh->setCellValue("G{$cdr}", round($cd->avg_rating ?? 0, 1) . ' / 5');
                    $sh->setCellValue("H{$cdr}", $perfCat);
                    foreach (['B','C','D','E','F','G'] as $c) $sh->getStyle("{$c}{$cdr}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $this->xlsApplyDataRow($sh, $cdr, 'H', $idx % 2 !== 0);
                    if ($pctCat < 60 && $cd->count > 0) $sh->getStyle("H{$cdr}")->getFont()->setBold(true)->getColor()->setRGB('CC0000');
                    $cdr++;
                }

                // Prioritas mini summary
                $cdr++;
                $this->xlsWriteSection($sh, $cdr, 'H', 'Distribusi Prioritas Tiket — ' . $year);
                $cdr++;
                $prData = Ticket::selectRaw('priority, COUNT(*) as count')->whereYear('created_at', $year)->groupBy('priority')->get()->keyBy('priority');
                $prHdr  = ['Urgent', 'Tinggi (High)', 'Normal', 'Rendah (Low)'];
                $prVals = ['urgent', 'high', 'normal', 'low'];
                $prCols = ['A','C','E','G'];
                foreach ($prHdr as $i => $h) {
                    $sh->setCellValue($prCols[$i] . $cdr, $h);
                    $sh->getStyle($prCols[$i] . $cdr)->applyFromArray(['font'=>['bold'=>true,'size'=>8],'fill'=>['fillType'=>Fill::FILL_SOLID,'startColor'=>['rgb'=>'444444']],'font'=>['bold'=>true,'size'=>8,'color'=>['rgb'=>'FFFFFF']],'alignment'=>['horizontal'=>Alignment::HORIZONTAL_CENTER],'borders'=>['allBorders'=>['borderStyle'=>Border::BORDER_THIN,'color'=>['rgb'=>'000000']]]]);
                    $sh->setCellValue($prCols[$i] . ($cdr + 1), $prData->get($prVals[$i])?->count ?? 0);
                    $sh->getStyle($prCols[$i] . ($cdr + 1))->applyFromArray(['font'=>['bold'=>true,'size'=>14,'name'=>'Arial'],'alignment'=>['horizontal'=>Alignment::HORIZONTAL_CENTER],'borders'=>['allBorders'=>['borderStyle'=>Border::BORDER_THIN,'color'=>['rgb'=>'CCCCCC']]]]);
                }
                $cdr += 2;

                // Tren Bulanan
                $cdr++;
                $this->xlsWriteSection($sh, $cdr, 'H', 'Tren Tiket Bulanan — ' . $year);
                $cdr++;
                $tkMHdr  = ['Bulan', 'Total Tiket', 'Terselesaikan', '% Selesai', 'Masih Terbuka', 'Tiket Urgent', 'MTTR Rata-rata', 'Status'];
                $tkMCols = ['A','B','C','D','E','F','G','H'];
                $this->xlsWriteColHeader($sh, $cdr, $tkMCols, $tkMHdr);
                $cdr++;

                for ($m = 1; $m <= 12; $m++) {
                    $tot = Ticket::whereYear('created_at', $year)->whereMonth('created_at', $m)->count();
                    $res = Ticket::where('status','resolved')->whereYear('created_at', $year)->whereMonth('created_at', $m)->count();
                    $opn = Ticket::whereIn('status',['open','in_progress'])->whereYear('created_at', $year)->whereMonth('created_at', $m)->count();
                    $urg = Ticket::where('priority','urgent')->whereYear('created_at', $year)->whereMonth('created_at', $m)->count();
                    $p   = $tot > 0 ? round(($res / $tot) * 100) : 0;
                    $stat = $p >= 80 ? 'Baik' : ($p >= 60 ? 'Sedang' : ($tot > 0 ? 'Perlu Perhatian' : '-'));

                    $sh->setCellValue("A{$cdr}", Carbon::create($year, $m, 1)->format('F Y'));
                    $sh->setCellValue("B{$cdr}", $tot);
                    $sh->setCellValue("C{$cdr}", $res);
                    $sh->setCellValue("D{$cdr}", $p . '%');
                    $sh->setCellValue("E{$cdr}", $opn);
                    $sh->setCellValue("F{$cdr}", $urg);
                    $sh->setCellValue("G{$cdr}", '-');
                    $sh->setCellValue("H{$cdr}", $stat);
                    foreach (['B','C','D','E','F','G','H'] as $c) $sh->getStyle("{$c}{$cdr}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $this->xlsApplyDataRow($sh, $cdr, 'H', $m % 2 === 0);
                    $cdr++;
                }
                $this->xlsSetColWidths($sh, ['A'=>22,'B'=>14,'C'=>16,'D'=>14,'E'=>16,'F'=>14,'G'=>18,'H'=>18]);

                /* ---------- SHEET 2 : Tiket Urgent Masih Terbuka ---------- */
                $sh2 = $spreadsheet->createSheet()->setTitle('Urgent Belum Selesai');
                $sh2->getPageSetup()->setOrientation('landscape');

                $this->xlsWriteDocHeader($sh2, 'H', 'TIKET URGENT MASIH TERBUKA — per ' . now()->format('d F Y'), $year);

                $urgHdr  = ['No. Tiket', 'Nama Tenant', 'Subjek', 'Kategori', 'Status', 'PIC / Assignee', 'Dibuat', 'Sudah Menunggu'];
                $urgCols = ['A','B','C','D','E','F','G','H'];
                $this->xlsWriteColHeader($sh2, 4, $urgCols, $urgHdr);

                $urgTks = Ticket::with(['user:id,name','assignee:id,name'])->where('priority','urgent')->whereIn('status',['open','in_progress'])->orderBy('created_at')->get();
                $udr = 5;
                foreach ($urgTks as $idx => $ut) {
                    $sh2->setCellValue("A{$udr}", $ut->ticket_number);
                    $sh2->setCellValue("B{$udr}", $ut->user?->name ?? '-');
                    $sh2->setCellValue("C{$udr}", $ut->subject);
                    $sh2->setCellValue("D{$udr}", ucfirst($ut->category));
                    $sh2->setCellValue("E{$udr}", ucfirst($ut->status));
                    $sh2->setCellValue("F{$udr}", $ut->assignee?->name ?? '-- Belum Ada PIC --');
                    $sh2->setCellValue("G{$udr}", $ut->created_at->format('d/m/Y '));
                    $sh2->setCellValue("H{$udr}", $ut->created_at->diffForHumans());
                    foreach (['A','D','E','G'] as $c) $sh2->getStyle("{$c}{$udr}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $this->xlsApplyDataRow($sh2, $udr, 'H', $idx % 2 !== 0);
                    $sh2->getStyle("F{$udr}")->getFont()->setBold(true);
                    $udr++;
                }
                if ($urgTks->isEmpty()) {
                    $sh2->mergeCells('A5:H5');
                    $sh2->setCellValue('A5', 'Tidak ada tiket urgent yang masih terbuka saat ini. Layanan berjalan dengan baik.');
                    $sh2->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sh2->getStyle('A5')->getFont()->setItalic(true);
                }
                $this->xlsSetColWidths($sh2, ['A'=>16,'B'=>22,'C'=>34,'D'=>14,'E'=>14,'F'=>24,'G'=>14,'H'=>20]);

                /* ---------- SHEET 3 : Detail Semua Tiket ---------- */
                $sh3 = $spreadsheet->createSheet()->setTitle('Detail Semua Tiket');
                $sh3->getPageSetup()->setOrientation('landscape');

                $this->xlsWriteDocHeader($sh3, 'L', 'DETAIL SEMUA TIKET LAYANAN — TAHUN ' . $year, $year);

                $tkDHdr  = ['No. Tiket', 'Nama Tenant', 'Email', 'Subjek', 'Kategori', 'Prioritas', 'Status', 'PIC', 'Tgl Dibuat', 'Tgl Selesai', 'MTTR (Jam)', 'Rating'];
                $tkDCols = ['A','B','C','D','E','F','G','H','I','J','K','L'];
                $this->xlsWriteColHeader($sh3, 4, $tkDCols, $tkDHdr);

                $tickets = Ticket::with(['user:id,name,email','assignee:id,name'])->whereYear('created_at', $year)->orderByDesc('created_at')->get();
                $tdr = 5;
                foreach ($tickets as $idx => $tk) {
                    $mttr  = $tk->resolved_at ? round($tk->created_at->diffInMinutes($tk->resolved_at) / 60, 1) : '-';
                    $prLbl = match ($tk->priority ?? 'normal') {'urgent'=>'Urgent','high'=>'Tinggi','normal'=>'Normal','low'=>'Rendah',default=>ucfirst($tk->priority ?? '-')};
                    $stLbl = match ($tk->status) {'open'=>'Terbuka','in_progress'=>'Diproses','pending'=>'Pending','resolved'=>'Selesai','closed'=>'Ditutup',default=>ucfirst($tk->status)};

                    $sh3->setCellValue("A{$tdr}", $tk->ticket_number);
                    $sh3->setCellValue("B{$tdr}", $tk->user?->name ?? '-');
                    $sh3->setCellValue("C{$tdr}", $tk->user?->email ?? '-');
                    $sh3->setCellValue("D{$tdr}", $tk->subject);
                    $sh3->setCellValue("E{$tdr}", ucfirst($tk->category));
                    $sh3->setCellValue("F{$tdr}", $prLbl);
                    $sh3->setCellValue("G{$tdr}", $stLbl);
                    $sh3->setCellValue("H{$tdr}", $tk->assignee?->name ?? '-');
                    $sh3->setCellValue("I{$tdr}", $tk->created_at->format('d/m/Y '));
                    $sh3->setCellValue("J{$tdr}", $tk->resolved_at?->format('d/m/Y ') ?? '-');
                    $sh3->setCellValue("K{$tdr}", $mttr);
                    $sh3->setCellValue("L{$tdr}", $tk->rating ? $tk->rating . '/5' : '-');
                    foreach (['A','E','F','G','I','J','K','L'] as $c) $sh3->getStyle("{$c}{$tdr}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $this->xlsApplyDataRow($sh3, $tdr, 'L', $idx % 2 !== 0);
                    if (($tk->priority ?? '') === 'urgent') $sh3->getStyle("F{$tdr}")->getFont()->setBold(true)->getColor()->setRGB('CC0000');
                    $tdr++;
                }
                $this->xlsSetColWidths($sh3, ['A'=>16,'B'=>22,'C'=>26,'D'=>32,'E'=>14,'F'=>12,'G'=>14,'H'=>20,'I'=>14,'J'=>14,'K'=>12,'L'=>10]);

                $spreadsheet->setActiveSheetIndex(0);
                $filename = "urban-kost-laporan-tiket-{$year}.xlsx";
                break;

            default:
                abort(404);
        }

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new XlsxWriter($spreadsheet);
            $writer->setIncludeCharts(false);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
            'Pragma'              => 'no-cache',
            'Expires'             => '0',
        ]);
    }

    // =========================================================================
    // EXPORT PDF — hitam putih, rapi, konsisten dengan view
    // =========================================================================


    // =========================================================================
    // EXPORT PDF — compact, efficient, no wasted whitespace
    // Auto page-break oleh dompdf, tidak ada .pb manual
    // =========================================================================
    private function exportPdf(string $section, int $year, int $month)
    {
        $idr = fn($v) => 'Rp ' . number_format((float)$v, 0, ',', '.');
        $pct = fn($a, $b) => $b > 0 ? round(($a / $b) * 100, 1) : 0;

        // ── CSS ──────────────────────────────────────────────────────────────
        $css = '<style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: "DejaVu Sans", Arial, sans-serif; font-size: 7.5px; color: #111; background: #fff; line-height: 1.4; }
        .page { margin: 14px 20px; }

        /* ── HEADER ── */
        .doc-hd { border: 1.5px solid #111; margin-bottom: 8px; }
        .doc-hd-top { background: #111; color: #fff; padding: 6px 12px; display: table; width: 100%; }
        .doc-hd-left { display: table-cell; vertical-align: middle; }
        .doc-hd-right { display: table-cell; vertical-align: middle; text-align: right; width: 190px; font-size: 6.5px; color: #ccc; }
        .doc-org { font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.4px; }
        .doc-sub-org { font-size: 6px; color: #aaa; margin-top: 1px; }
        .doc-meta { background: #f2f2f2; padding: 3px 12px; font-size: 6.5px; color: #555; border-top: 1px solid #ddd; display: table; width: 100%; }
        .doc-meta-l { display: table-cell; }
        .doc-meta-r { display: table-cell; text-align: right; }
        .doc-ttl { border-left: 1.5px solid #111; border-right: 1.5px solid #111; border-bottom: 1.5px solid #111; padding: 6px 12px 5px; text-align: center; margin-bottom: 8px; }
        .doc-ttl h1 { font-size: 9.5px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.4px; }
        .doc-ttl p  { font-size: 6.5px; color: #555; margin-top: 1px; }

        /* ── KPI BOX ── */
        .kpi { border: 1.5px solid #111; margin-bottom: 8px; }
        .kpi-hd { background: #111; color: #fff; font-size: 6.5px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.07em; padding: 3px 8px; }
        .kpi-row { display: table; width: 100%; border-collapse: collapse; }
        .kpi-cell { display: table-cell; border: 1px solid #ccc; padding: 6px 8px; text-align: center; vertical-align: middle; background: #fafafa; }
        .kpi-val { font-size: 9.5px; font-weight: bold; color: #111; line-height: 1.1; margin-bottom: 2px; }
        .kpi-lbl { font-size: 5.8px; text-transform: uppercase; letter-spacing: 0.05em; color: #666; font-weight: bold; }

        /* ── SECTION HEADER ── */
        .sec { background: #222; color: #fff; font-size: 6.5px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.06em; padding: 4px 9px; border: 1.5px solid #111; border-bottom: none; margin-top: 10px; }

        /* ── TABLE ── */
        table { width: 100%; border-collapse: collapse; border: 1.5px solid #111; font-size: 7px; margin-bottom: 0; }
        thead th { background: #333; color: #fff; padding: 4px 6px; font-size: 6.5px; font-weight: bold; text-align: left; border: 1px solid #444; white-space: nowrap; }
        thead th.c { text-align: center; }
        thead th.r { text-align: right; }
        tbody tr:nth-child(even) { background: #f5f5f5; }
        tbody tr { border-bottom: 1px solid #ddd; }
        tbody td { padding: 3.5px 6px; border-right: 1px solid #e0e0e0; color: #111; vertical-align: middle; }
        tbody td:last-child { border-right: none; }
        tbody td.c  { text-align: center; }
        tbody td.r  { text-align: right; }
        tbody td.b  { font-weight: bold; }
        tbody td.mn { font-family: "DejaVu Sans Mono", monospace; font-size: 6.5px; }
        tbody td.sm { font-size: 6.5px; color: #555; }
        tfoot td { background: #111; color: #fff; font-weight: bold; font-size: 7.5px; padding: 4px 6px; border: 1px solid #333; }
        tfoot td.r { text-align: right; }
        tfoot td.c { text-align: center; }
        .hi-dk { background: #e0e0e0 !important; font-weight: bold; }
        .hi-rd { background: #fff5f5 !important; }
        .hi-top { background: #ebebeb !important; font-weight: bold; }

        /* ── 2-COL LAYOUT untuk tabel berdampingan ── */
        .two-col { display: table; width: 100%; border-collapse: separate; border-spacing: 6px 0; margin-bottom: 0; }
        .col-l { display: table-cell; width: 55%; vertical-align: top; }
        .col-r { display: table-cell; width: 45%; vertical-align: top; }

        /* ── NOTE ── */
        .note { border: 1px solid #aaa; border-left: 3px solid #333; background: #f8f8f8; padding: 4px 9px; font-size: 6.5px; color: #444; margin: 6px 0; line-height: 1.4; }
        .note strong { color: #111; }

        /* ── FOOTER ── */
        .doc-ftr { border-top: 1px solid #aaa; margin-top: 12px; padding-top: 3px; font-size: 5.8px; color: #888; text-align: center; }

        /* ── PAGE BREAK ── */
        .pb { page-break-after: always; }
        </style>';

        // ── Shared header builder ─────────────────────────────────────────────
        $hdr = function (string $title, string $sub) use ($year): string {
            $tgl = now()->format('d F Y');
            return "
            <div class='doc-hd'>
                <div class='doc-hd-top'>
                    <div class='doc-hd-left'>
                        <div class='doc-org'>Urban Kost</div>
                        <div class='doc-sub-org'>Sistem Manajemen Properti &nbsp;|&nbsp; Laporan Internal Admin</div>
                    </div>
                    <div class='doc-hd-right'>
                        <div>Tahun: <strong style='color:#fff'>{$year}</strong></div>
                        <div>Cetak: {$tgl}</div>
                        <div>Klasifikasi: Rahasia</div>
                    </div>
                </div>
                <div class='doc-meta'>
                    <div class='doc-meta-l'>Urban Kost Management System &nbsp;|&nbsp; Laporan Analitik Admin</div>
                    <div class='doc-meta-r'>Tahun: {$year} &nbsp;|&nbsp; {$tgl}</div>
                </div>
            </div>
            <div class='doc-ttl'><h1>{$title}</h1><p>{$sub}</p></div>";
        };

        $ftr = "<div class='doc-ftr'>Urban Kost Management System &nbsp;&bull;&nbsp; Laporan Analitik Internal &nbsp;&bull;&nbsp; Dicetak: " . now()->format('d F Y') . "</div></div>";
        // Page break: tutup halaman 1, buka halaman 2 dengan header baru
        $pb = $ftr . "<div class='pb'></div><div class='page'>";

        $html = $css . "<div class='page'>";

        switch ($section) {

            // =================================================================
            // PENDAPATAN
            // Semua dalam satu aliran: KPI → Rekap Bulanan → Top 5 → Overdue → Detail Invoice
            // =================================================================
            case 'pendapatan':
                $totalRev  = (float)Invoice::where('status', 'paid')->whereYear('period_start', $year)->sum('total_amount');
                $ovAmt     = (float)Invoice::where('status', 'overdue')->sum('total_amount');
                $paidCnt   = Invoice::where('status', 'paid')->whereYear('created_at', $year)->count();
                $ovCnt     = Invoice::where('status', 'overdue')->count();
                $unpaidCnt = Invoice::whereIn('status', ['unpaid', 'overdue'])->whereYear('created_at', $year)->count();

                // --- Rekap bulanan ---
                $monthRows = ''; $running = 0; $totInv = 0; $totLunas = 0;
                for ($m = 1; $m <= 12; $m++) {
                    $p  = (float)Invoice::where('status','paid')->whereYear('period_start',$year)->whereMonth('period_start',$m)->sum('total_amount');
                    $u  = (float)Invoice::whereIn('status',['unpaid','overdue'])->whereYear('period_start',$year)->whereMonth('period_start',$m)->sum('total_amount');
                    $i  = Invoice::whereYear('period_start',$year)->whereMonth('period_start',$m)->count();
                    $ip = Invoice::where('status','paid')->whereYear('period_start',$year)->whereMonth('period_start',$m)->count();
                    $iu = $i - $ip;
                    $running += $p; $totInv += $i; $totLunas += $ip;
                    $pc = $i > 0 ? round(($ip / $i) * 100) : 0;
                    $monthRows .= "<tr>
                        <td class='b'>" . Carbon::create($year,$m,1)->format('F Y') . "</td>
                        <td class='r b'>{$idr($p)}</td>
                        <td class='r sm'>{$idr($u)}</td>
                        <td class='c'>{$i}</td><td class='c'>{$ip}</td><td class='c'>{$iu}</td>
                        <td class='c'>{$pc}%</td>
                        <td class='r sm'>{$idr($running)}</td>
                    </tr>";
                }

                // --- Top 5 ---
                $topRooms  = Invoice::where('status','paid')->whereYear('period_start',$year)
                    ->select('room_id', DB::raw('SUM(total_amount) as total_revenue'), DB::raw('COUNT(*) as invoice_count'))
                    ->groupBy('room_id')->with('room:id,room_number,room_name')
                    ->orderByDesc('total_revenue')->limit(5)->get();
                $top5Total = $topRooms->sum('total_revenue');
                $topRows   = '';
                foreach ($topRooms as $i => $tr) {
                    $pctR   = $totalRev > 0 ? round(($tr->total_revenue / $totalRev) * 100, 1) : 0;
                    $cls    = $i < 3 ? 'hi-top' : '';
                    $topRows .= "<tr class='{$cls}'>
                        <td class='c b'>" . ($i+1) . "</td>
                        <td class='b'>{$tr->room?->room_number}</td>
                        <td class='sm'>" . htmlspecialchars($tr->room?->room_name ?? '-') . "</td>
                        <td class='c'>{$tr->invoice_count}</td>
                        <td class='r b'>{$idr($tr->total_revenue)}</td>
                        <td class='c'>" . ($totalRev > 0 ? round(($tr->total_revenue/$totalRev)*100,1) : 0) . "%</td>
                    </tr>";
                }
                $top5Pct = $totalRev > 0 ? round(($top5Total / $totalRev) * 100, 1) : 0;

                // --- Overdue aktif ---
                $overdueList = Invoice::with(['user:id,name','room:id,room_number'])->where('status','overdue')->orderByDesc('due_date')->get();
                $ovRows = '';
                foreach ($overdueList as $od) {
                    $days = $od->due_date ? (int)now()->diffInDays($od->due_date) : 0;
                    $ovRows .= "<tr class='hi-rd'>
                        <td class='mn sm'>{$od->invoice_number}</td>
                        <td class='b'>" . htmlspecialchars($od->user?->name ?? '-') . "</td>
                        <td class='c'>{$od->room?->room_number}</td>
                        <td class='r b'>{$idr($od->total_amount)}</td>
                        <td class='c sm'>" . ($od->due_date?->format('d M Y') ?? '-') . "</td>
                        <td class='c b'>{$days} hari</td>
                    </tr>";
                }

                // --- Detail invoice ---
                $invoices = Invoice::with(['user:id,name','room:id,room_number'])
                    ->whereYear('created_at', $year)->orderByDesc('created_at')->get();
                $invRows = '';
                foreach ($invoices->take(250) as $inv) {
                    $st  = strtoupper($inv->status);
                    $cls = $inv->status === 'overdue' ? 'hi-rd' : '';
                    $invRows .= "<tr class='{$cls}'>
                        <td class='mn sm'>{$inv->invoice_number}</td>
                        <td class='b'>" . htmlspecialchars($inv->user?->name ?? '-') . "</td>
                        <td class='c'>{$inv->room?->room_number}</td>
                        <td class='c sm'>" . $this->invoiceTypeLabel($inv->invoice_type ?? '') . "</td>
                        <td class='r b'>{$idr($inv->total_amount)}</td>
                        <td class='c sm'>" . ($inv->due_date?->format('d M Y') ?? '-') . "</td>
                        <td class='c b'>{$st}</td>
                        <td class='c sm'>" . ($inv->paid_at?->format('d M Y') ?? '—') . "</td>
                    </tr>";
                }
                $moreNote = $invoices->count() > 250
                    ? "<div class='note'>... dan <strong>" . ($invoices->count()-250) . " invoice</strong> lainnya. Gunakan export Excel untuk data lengkap.</div>"
                    : '';

                $html .= $hdr('LAPORAN PENDAPATAN &amp; KEUANGAN', 'Rekap bulanan, top 5 kamar, overdue aktif, dan detail arus kas &mdash; Tahun ' . $year);

                // KPI
                $html .= "<div class='kpi'>
                    <div class='kpi-hd'>Ringkasan Keuangan &mdash; Tahun {$year}</div>
                    <div class='kpi-row'>
                        <div class='kpi-cell'><div class='kpi-val'>{$idr($totalRev)}</div><div class='kpi-lbl'>Total Pendapatan Lunas</div></div>
                        <div class='kpi-cell'><div class='kpi-val'>{$paidCnt}</div><div class='kpi-lbl'>Invoice Lunas</div></div>
                        <div class='kpi-cell'><div class='kpi-val'>{$unpaidCnt}</div><div class='kpi-lbl'>Belum Terbayar</div></div>
                        <div class='kpi-cell'><div class='kpi-val'>{$idr($ovAmt)}</div><div class='kpi-lbl'>Total Tunggakan Overdue</div></div>
                        <div class='kpi-cell'><div class='kpi-val'>{$ovCnt}</div><div class='kpi-lbl'>Invoice Overdue Aktif</div></div>
                    </div>
                </div>";

                // Rekap Bulanan + Top 5 berdampingan (2 kolom)
                $html .= "<div class='two-col'>
                    <div class='col-l'>
                        <div class='sec' style='margin-top:0'>A. Rekap Pendapatan Bulanan &mdash; {$year}</div>
                        <table>
                            <thead><tr>
                                <th>Bulan</th><th class='r'>Lunas (Rp)</th><th class='r'>Belum (Rp)</th>
                                <th class='c'>Inv.</th><th class='c'>✓</th><th class='c'>✗</th>
                                <th class='c'>%</th><th class='r'>Kumulatif (Rp)</th>
                            </tr></thead>
                            <tbody>{$monthRows}</tbody>
                            <tfoot><tr>
                                <td class='b'>TOTAL</td><td class='r'>{$idr($totalRev)}</td><td></td>
                                <td class='c'>{$totInv}</td><td class='c'>{$totLunas}</td><td></td><td></td><td class='r'>{$idr($totalRev)}</td>
                            </tr></tfoot>
                        </table>
                    </div>
                    <div class='col-r'>
                        <div class='sec' style='margin-top:0'>B. Top 5 Kamar Menghasilkan &mdash; {$year}</div>
                        <table>
                            <thead><tr>
                                <th class='c'>#</th><th>Kamar</th><th>Nama</th>
                                <th class='c'>Inv.</th><th class='r'>Pendapatan (Rp)</th><th class='c'>%</th>
                            </tr></thead>
                            <tbody>{$topRows}</tbody>
                            <tfoot><tr>
                                <td colspan='4' class='b'>Kontribusi Top 5</td>
                                <td class='r'>{$idr($top5Total)}</td>
                                <td class='c'>{$top5Pct}%</td>
                            </tr></tfoot>
                        </table>
                        <div class='note' style='margin-top:4px'><strong>Ket:</strong> Baris gelap = 3 kamar teratas. Hanya invoice <em>paid</em>.</div>
                    </div>
                </div>";

                // Overdue aktif + Detail invoice → halaman 2
                $html .= $pb;
                $html .= $hdr('LAPORAN PENDAPATAN &amp; KEUANGAN — Halaman 2', 'Invoice overdue aktif &amp; detail arus kas &mdash; Tahun ' . $year);

                if ($overdueList->isNotEmpty()) {
                    $html .= "<div class='sec'>C. Invoice Overdue Aktif &mdash; Perlu Tindakan Segera ({$overdueList->count()} invoice)</div>
                    <table>
                        <thead><tr>
                            <th>No. Invoice</th><th>Tenant</th><th class='c'>Kamar</th>
                            <th class='r'>Tagihan (Rp)</th><th class='c'>Jatuh Tempo</th><th class='c'>Hari Terlambat</th>
                        </tr></thead>
                        <tbody>{$ovRows}</tbody>
                        <tfoot><tr>
                            <td colspan='3' class='b'>TOTAL TUNGGAKAN</td>
                            <td class='r'>{$idr($ovAmt)}</td><td colspan='2'></td>
                        </tr></tfoot>
                    </table>";
                } else {
                    $html .= "<div class='note' style='margin-top:8px'><strong>C. Overdue Aktif:</strong> Tidak ada invoice overdue aktif saat ini.</div>";
                }

                // Detail invoice
                $secD = $overdueList->isNotEmpty() ? 'D' : 'C';
                $html .= "<div class='sec'>{$secD}. Detail Semua Invoice &mdash; {$year}" . ($invoices->count() > 250 ? ' (250 pertama)' : '') . "</div>
                <table>
                    <thead><tr>
                        <th>No. Invoice</th><th>Tenant</th><th class='c'>Kamar</th><th class='c'>Tipe</th>
                        <th class='r'>Tagihan (Rp)</th><th class='c'>Jatuh Tempo</th><th class='c'>Status</th><th class='c'>Tgl Bayar</th>
                    </tr></thead>
                    <tbody>{$invRows}</tbody>
                </table>
                {$moreNote}
                <div class='note'><strong>Ket:</strong> Baris merah muda = overdue. Kolom ✓ = lunas, ✗ = belum pada tabel rekap bulanan.</div>";

                $html .= $ftr;
                $filename = "urban-kost-laporan-pendapatan-{$year}.pdf";
                break;

            // =================================================================
            // BOOKING
            // KPI → 2-col (Statistik Kamar | Tren Bulanan) → Kamar Jarang → Riwayat Detail
            // =================================================================
            case 'booking':
                $totalBk  = Booking::whereYear('created_at', $year)->count();
                $appBk    = Booking::whereIn('status',['approved','active','completed'])->whereYear('created_at',$year)->count();
                $canBk    = Booking::whereIn('status',['cancelled','rejected'])->whereYear('created_at',$year)->count();
                $pendBk   = Booking::whereIn('status',['pending','under_review'])->whereYear('created_at',$year)->count();
                $avgDurBk = round(Booking::whereYear('created_at',$year)->avg('duration_months') ?? 0, 1);
                $appRate  = $pct($appBk, $totalBk);
                $allRooms = Room::orderBy('room_number')->get();

                // Statistik semua kamar
                $bsRows = '';
                foreach ($allRooms as $room) {
                    $bkgs    = Booking::where('room_id',$room->id)->whereYear('created_at',$year)->get();
                    $revenue = (float)Invoice::where('status','paid')->where('room_id',$room->id)->whereYear('period_start',$year)->sum('total_amount');
                    $actBk   = Booking::where('room_id',$room->id)->whereIn('status',['active','approved'])->with('user:id,name')->first();
                    $stL     = match ($room->status) {'occupied'=>'Terisi','available'=>'Kosong','reserved'=>'Dipesan','maintenance'=>'Perbaikan',default=>$room->status};
                    $cls     = $bkgs->count() === 0 ? 'hi-rd' : '';
                    $bsRows .= "<tr class='{$cls}'>
                        <td class='b'>{$room->room_number}</td>
                        <td class='sm'>" . htmlspecialchars($room->room_name ?? '-') . "</td>
                        <td class='c sm'>{$stL}</td>
                        <td class='c b'>{$bkgs->count()}</td>
                        <td class='c'>" . $bkgs->whereIn('status',['approved','active','completed'])->count() . "</td>
                        <td class='c'>" . $bkgs->whereIn('status',['cancelled','rejected'])->count() . "</td>
                        <td class='r sm'>" . ($revenue > 0 ? $idr($revenue) : '—') . "</td>
                        <td class='sm'>" . htmlspecialchars($actBk?->user?->name ?? '—') . "</td>
                    </tr>";
                }

                // Tren bulanan
                $trendRows = ''; $totTot = $totApp = $totCan = $totPend = 0;
                for ($m = 1; $m <= 12; $m++) {
                    $tot  = Booking::whereYear('created_at',$year)->whereMonth('created_at',$m)->count();
                    $app  = Booking::whereIn('status',['approved','active','completed'])->whereYear('created_at',$year)->whereMonth('created_at',$m)->count();
                    $can  = Booking::whereIn('status',['cancelled','rejected'])->whereYear('created_at',$year)->whereMonth('created_at',$m)->count();
                    $pend = Booking::whereIn('status',['pending','under_review'])->whereYear('created_at',$year)->whereMonth('created_at',$m)->count();
                    $ap   = $pct($app,$tot);
                    $totTot += $tot; $totApp += $app; $totCan += $can; $totPend += $pend;
                    $trendRows .= "<tr>
                        <td>" . Carbon::create($year,$m,1)->format('M Y') . "</td>
                        <td class='c'>{$tot}</td><td class='c b'>{$app}</td>
                        <td class='c'>{$can}</td><td class='c sm'>{$pend}</td><td class='c'>{$ap}%</td>
                    </tr>";
                }

                // Kamar jarang
                $leastBooked = $allRooms->map(function ($room) use ($year) {
                    $bkgs = Booking::where('room_id',$room->id)->whereYear('created_at',$year)->get();
                    return ['room'=>$room,'total_bookings'=>$bkgs->count(),'days_vacant'=>$room->status==='available'?(int)(optional($bkgs->sortByDesc('updated_at')->first()?->updated_at??now())->diffInDays(now())):0];
                })->sortBy('total_bookings')->take(5);
                $lbRows = '';
                foreach ($leastBooked as $lb) {
                    $cls = $lb['total_bookings'] === 0 ? 'hi-rd' : '';
                    $lbRows .= "<tr class='{$cls}'>
                        <td class='b'>{$lb['room']->room_number}</td>
                        <td class='sm'>" . htmlspecialchars($lb['room']->room_name ?? '-') . "</td>
                        <td class='c sm'>{$lb['room']->status}</td>
                        <td class='r sm'>{$idr($lb['room']->price)}</td>
                        <td class='c b'>{$lb['total_bookings']}x</td>
                        <td class='c sm'>" . ($lb['days_vacant'] > 0 ? '~'.$lb['days_vacant'].' hari' : '-') . "</td>
                    </tr>";
                }

                // Riwayat detail
                $bookings = Booking::with(['user:id,name','room:id,room_number'])->whereYear('created_at',$year)->orderByDesc('created_at')->get();
                $bkRows = '';
                foreach ($bookings->take(200) as $bk) {
                    $stL = match ($bk->status) {'pending'=>'Pending','under_review'=>'Ditinjau','approved'=>'Disetujui','active'=>'Aktif','completed'=>'Selesai','cancelled'=>'Dibatalkan','rejected'=>'Ditolak','waiting_list'=>'WL',default=>$bk->status};
                    $cls = in_array($bk->status,['cancelled','rejected']) ? 'hi-dk' : '';
                    $bkRows .= "<tr class='{$cls}'>
                        <td class='mn sm'>{$bk->booking_code}</td>
                        <td class='b'>" . htmlspecialchars($bk->user?->name ?? $bk->full_name ?? '-') . "</td>
                        <td class='c'>{$bk->room?->room_number}</td>
                        <td class='c sm'>" . ucfirst($bk->booking_type ?? 'regular') . "</td>
                        <td class='c sm'>" . ($bk->check_in_date?->format('d M Y') ?? '-') . "</td>
                        <td class='c sm'>{$bk->duration_months} bln</td>
                        <td class='c b'>{$stL}</td>
                        <td class='c sm'>{$bk->created_at->format('d M Y')}</td>
                    </tr>";
                }
                $bkMore = $bookings->count() > 200
                    ? "<div class='note'>... dan <strong>" . ($bookings->count()-200) . " booking</strong> lainnya. Gunakan export Excel untuk data lengkap.</div>"
                    : '';

                $html .= $hdr('LAPORAN RIWAYAT &amp; STATISTIK BOOKING', 'Statistik per kamar, tren bulanan, kamar jarang &amp; riwayat detail &mdash; Tahun ' . $year);

                // KPI
                $html .= "<div class='kpi'>
                    <div class='kpi-hd'>Ringkasan Booking &mdash; Tahun {$year}</div>
                    <div class='kpi-row'>
                        <div class='kpi-cell'><div class='kpi-val'>{$totalBk}</div><div class='kpi-lbl'>Total Booking</div></div>
                        <div class='kpi-cell'><div class='kpi-val'>{$appBk} ({$appRate}%)</div><div class='kpi-lbl'>Disetujui / Aktif</div></div>
                        <div class='kpi-cell'><div class='kpi-val'>{$canBk}</div><div class='kpi-lbl'>Dibatalkan / Ditolak</div></div>
                        <div class='kpi-cell'><div class='kpi-val'>{$pendBk}</div><div class='kpi-lbl'>Pending / Review</div></div>
                        <div class='kpi-cell'><div class='kpi-val'>{$avgDurBk} bln</div><div class='kpi-lbl'>Avg Durasi Sewa</div></div>
                    </div>
                </div>";

                // Statistik kamar + tren 2-col
                $html .= "<div class='two-col'>
                    <div class='col-l'>
                        <div class='sec' style='margin-top:0'>A. Statistik Booking Per Kamar &mdash; {$year}</div>
                        <div class='note' style='margin:0 0 0 0;border-top:none'><strong>Ket:</strong> Merah = 0 booking, perlu evaluasi.</div>
                        <table>
                            <thead><tr>
                                <th>Kamar</th><th>Nama</th><th class='c'>Status</th>
                                <th class='c'>Total</th><th class='c'>✓</th><th class='c'>✗</th>
                                <th class='r'>Pendapatan</th><th>Tenant Aktif</th>
                            </tr></thead>
                            <tbody>{$bsRows}</tbody>
                        </table>
                    </div>
                    <div class='col-r'>
                        <div class='sec' style='margin-top:0'>B. Tren Booking Bulanan &mdash; {$year}</div>
                        <table>
                            <thead><tr>
                                <th>Bulan</th><th class='c'>Total</th><th class='c'>✓</th>
                                <th class='c'>✗</th><th class='c'>Pend.</th><th class='c'>%</th>
                            </tr></thead>
                            <tbody>{$trendRows}</tbody>
                            <tfoot><tr>
                                <td class='b'>TOTAL</td><td class='c'>{$totTot}</td><td class='c'>{$totApp}</td>
                                <td class='c'>{$totCan}</td><td class='c'>{$totPend}</td><td></td>
                            </tr></tfoot>
                        </table>

                        <div class='sec'>C. Kamar Paling Jarang Dibooking</div>
                        <table>
                            <thead><tr>
                                <th>Kamar</th><th>Nama</th><th class='c'>Status</th>
                                <th class='r'>Harga/Bln</th><th class='c'>Booking</th><th class='c'>Est. Kosong</th>
                            </tr></thead>
                            <tbody>{$lbRows}</tbody>
                        </table>
                        <div class='note'><strong>Ket:</strong> Merah = 0 booking. Tinjau harga/promosi.</div>
                    </div>
                </div>";

                // Riwayat detail → halaman 2
                $html .= $pb;
                $html .= $hdr('LAPORAN RIWAYAT &amp; STATISTIK BOOKING — Halaman 2', 'Riwayat detail semua booking &mdash; Tahun ' . $year);
                $html .= "<div class='sec'>D. Riwayat Detail Booking &mdash; {$year}" . ($bookings->count() > 200 ? ' (200 pertama)' : '') . "</div>
                <table>
                    <thead><tr>
                        <th>Kode Booking</th><th>Tenant</th><th class='c'>Kamar</th><th class='c'>Tipe</th>
                        <th class='c'>Check-in</th><th class='c'>Durasi</th><th class='c'>Status</th><th class='c'>Dibuat</th>
                    </tr></thead>
                    <tbody>{$bkRows}</tbody>
                </table>
                {$bkMore}
                <div class='note'><strong>Ket:</strong> Baris gelap = booking dibatalkan/ditolak. ✓ = disetujui, ✗ = dibatalkan, Pend. = pending.</div>";

                $html .= $ftr;
                $filename = "urban-kost-laporan-booking-{$year}.pdf";
                break;

            // =================================================================
            // KETERLAMBATAN
            // KPI → 2-col (Overdue | Rekap Bulanan) → Tenant Sering → Detail Terlambat
            // =================================================================
            case 'keterlambatan':
                $paidCntLt = Invoice::where('status','paid')->whereYear('created_at',$year)->count();
                $lateCntLt = Invoice::where('status','paid')->whereYear('created_at',$year)->whereRaw('paid_at > due_date')->count();
                $overNow   = Invoice::where('status','overdue')->count();
                $overAmt   = (float)Invoice::where('status','overdue')->sum('total_amount');
                $lateRate  = $pct($lateCntLt, $paidCntLt);
                $avgLate   = round(Invoice::where('status','paid')->whereYear('created_at',$year)->whereRaw('paid_at > due_date')->avg(DB::raw('DATEDIFF(paid_at, due_date)')) ?? 0, 1);

                // Overdue aktif
                $overdueList = Invoice::with(['user:id,name','room:id,room_number'])->where('status','overdue')->orderByDesc('due_date')->get();
                $ovRows = '';
                foreach ($overdueList as $od) {
                    $days = $od->due_date ? (int)now()->diffInDays($od->due_date) : 0;
                    $ovRows .= "<tr class='hi-rd'>
                        <td class='mn sm'>{$od->invoice_number}</td>
                        <td class='b'>" . htmlspecialchars($od->user?->name ?? '-') . "</td>
                        <td class='c'>{$od->room?->room_number}</td>
                        <td class='r b'>{$idr($od->total_amount)}</td>
                        <td class='c sm'>" . ($od->due_date?->format('d M Y') ?? '-') . "</td>
                        <td class='c b'>{$days} hari</td>
                    </tr>";
                }

                // Rekap bulanan
                $lateData = Invoice::where('status','paid')->whereYear('period_start',$year)->whereRaw('paid_at > due_date')
                    ->select(DB::raw('MONTH(period_start) as m'),DB::raw('COUNT(*) as cnt'),DB::raw('AVG(DATEDIFF(paid_at, due_date)) as avg_d'),DB::raw('MAX(DATEDIFF(paid_at, due_date)) as max_d'))
                    ->groupBy('m')->get()->keyBy('m');
                $ltMonRows = '';
                for ($m = 1; $m <= 12; $m++) {
                    $cnt   = $lateData->get($m)?->cnt ?? 0;
                    $avg   = round($lateData->get($m)?->avg_d ?? 0, 1);
                    $max   = $lateData->get($m)?->max_d ?? 0;
                    $lunas = Invoice::where('status','paid')->whereYear('period_start',$year)->whereMonth('period_start',$m)->count();
                    $ov    = Invoice::where('status','overdue')->whereYear('period_start',$year)->whereMonth('period_start',$m)->count();
                    $pctLt = $lunas > 0 ? round(($cnt/$lunas)*100,1) : 0;
                    $cat   = $avg > 14 ? 'Kritis' : ($avg > 7 ? 'Perhatian' : ($cnt > 0 ? 'Ringan' : '-'));
                    $cls   = $avg > 14 ? 'hi-dk' : '';
                    $ltMonRows .= "<tr class='{$cls}'>
                        <td>" . Carbon::create($year,$m,1)->format('M Y') . "</td>
                        <td class='c'>{$cnt}</td><td class='c'>{$avg} hr</td><td class='c'>{$max} hr</td>
                        <td class='c'>{$lunas}</td><td class='c'>{$ov}</td>
                        <td class='c'>{$pctLt}%</td><td class='c sm'>{$cat}</td>
                    </tr>";
                }

                // Tenant sering terlambat
                $chronLate = Invoice::where('status','paid')->whereYear('period_start',$year)->whereRaw('paid_at > due_date')
                    ->select('user_id',DB::raw('COUNT(*) as late_count'),DB::raw('AVG(DATEDIFF(paid_at, due_date)) as avg_days'),DB::raw('SUM(total_amount) as total_paid'))
                    ->groupBy('user_id')->having('late_count','>=',2)->with('user:id,name,email,phone')
                    ->orderByDesc('late_count')->get();
                $clRows = '';
                foreach ($chronLate as $i => $cl) {
                    $clRows .= "<tr>
                        <td class='c b'>" . ($i+1) . "</td>
                        <td class='b'>" . htmlspecialchars($cl->user?->name ?? '-') . "</td>
                        <td class='sm'>" . ($cl->user?->email ?? '-') . "</td>
                        <td class='sm'>" . ($cl->user?->phone ?? '-') . "</td>
                        <td class='c b'>{$cl->late_count}x</td>
                        <td class='c'>" . round($cl->avg_days,1) . " hari</td>
                        <td class='r'>{$idr($cl->total_paid)}</td>
                    </tr>";
                }

                // Detail invoice terlambat
                $lateInvs = Invoice::with(['user:id,name','room:id,room_number'])
                    ->where('status','paid')->whereYear('period_start',$year)->whereRaw('paid_at > due_date')
                    ->orderByDesc(DB::raw('DATEDIFF(paid_at, due_date)'))->get();
                $ltRows = '';
                foreach ($lateInvs->take(200) as $li) {
                    $dl  = $li->paid_at && $li->due_date ? (int)$li->paid_at->diffInDays($li->due_date) : 0;
                    $cat = $dl > 14 ? 'Kritis' : ($dl > 7 ? 'Sedang' : 'Ringan');
                    $cls = $dl > 14 ? 'hi-dk' : '';
                    $ltRows .= "<tr class='{$cls}'>
                        <td class='mn sm'>{$li->invoice_number}</td>
                        <td class='b'>" . htmlspecialchars($li->user?->name ?? '-') . "</td>
                        <td class='c'>{$li->room?->room_number}</td>
                        <td class='r'>{$idr($li->total_amount)}</td>
                        <td class='c sm'>" . ($li->due_date?->format('d M Y') ?? '-') . "</td>
                        <td class='c sm'>" . ($li->paid_at?->format('d M Y') ?? '-') . "</td>
                        <td class='c b'>{$dl} hari</td>
                        <td class='c sm'>{$cat}</td>
                    </tr>";
                }
                $ltMore = $lateInvs->count() > 200
                    ? "<div class='note'>... dan <strong>" . ($lateInvs->count()-200) . " invoice</strong> lainnya. Gunakan export Excel untuk data lengkap.</div>"
                    : '';

                $html .= $hdr('LAPORAN PEMBAYARAN &amp; KETERLAMBATAN', 'Overdue aktif, rekap bulanan, tenant sering terlambat &amp; detail &mdash; Tahun ' . $year);

                // KPI
                $html .= "<div class='kpi'>
                    <div class='kpi-hd'>Ringkasan Keterlambatan &mdash; Tahun {$year}</div>
                    <div class='kpi-row'>
                        <div class='kpi-cell'><div class='kpi-val'>{$paidCntLt}</div><div class='kpi-lbl'>Invoice Lunas {$year}</div></div>
                        <div class='kpi-cell'><div class='kpi-val'>{$lateCntLt} ({$lateRate}%)</div><div class='kpi-lbl'>Dibayar Terlambat</div></div>
                        <div class='kpi-cell'><div class='kpi-val'>{$overNow}</div><div class='kpi-lbl'>Overdue Aktif</div></div>
                        <div class='kpi-cell'><div class='kpi-val'>{$idr($overAmt)}</div><div class='kpi-lbl'>Total Tunggakan</div></div>
                        <div class='kpi-cell'><div class='kpi-val'>{$avgLate} hari</div><div class='kpi-lbl'>Avg Hari Terlambat</div></div>
                    </div>
                </div>
                <div class='note'><strong>Target:</strong> Tingkat keterlambatan &lt;20% &bull; Avg hari terlambat &lt;7 hari. Kritis = &gt;14 hari (baris gelap).</div>";

                // 2-col: overdue + rekap bulanan
                $ovSection = $overdueList->isNotEmpty()
                    ? "<div class='sec' style='margin-top:0'>A. Invoice Overdue Aktif ({$overdueList->count()} invoice)</div>
                       <table>
                           <thead><tr>
                               <th>No. Invoice</th><th>Tenant</th><th class='c'>Kamar</th>
                               <th class='r'>Tagihan (Rp)</th><th class='c'>Jatuh Tempo</th><th class='c'>Hari Tlbt</th>
                           </tr></thead>
                           <tbody>{$ovRows}</tbody>
                           <tfoot><tr>
                               <td colspan='3' class='b'>TOTAL TUNGGAKAN</td>
                               <td class='r'>{$idr($overAmt)}</td><td colspan='2'></td>
                           </tr></tfoot>
                       </table>"
                    : "<div class='sec' style='margin-top:0'>A. Invoice Overdue Aktif</div>
                       <div class='note'>Tidak ada invoice overdue aktif saat ini.</div>";

                $secB = 'B';
                $html .= "<div class='two-col'>
                    <div class='col-l'>{$ovSection}</div>
                    <div class='col-r'>
                        <div class='sec' style='margin-top:0'>{$secB}. Rekap Bulanan Keterlambatan &mdash; {$year}</div>
                        <table>
                            <thead><tr>
                                <th>Bulan</th><th class='c'>Tlbt</th><th class='c'>Avg hr</th><th class='c'>Max hr</th>
                                <th class='c'>Lunas</th><th class='c'>OD</th><th class='c'>%</th><th class='c'>Kat.</th>
                            </tr></thead>
                            <tbody>{$ltMonRows}</tbody>
                        </table>
                    </div>
                </div>";

                $secC = 'C';
                // Tenant sering + detail → halaman 2
                $html .= $pb;
                $html .= $hdr('LAPORAN PEMBAYARAN &amp; KETERLAMBATAN — Halaman 2', 'Tenant sering terlambat &amp; detail invoice terlambat &mdash; Tahun ' . $year);
                // Tenant sering + detail (2-col jika ada tenant sering)
                if ($chronLate->isNotEmpty()) {
                    $html .= "<div class='two-col'>
                        <div class='col-r' style='width:40%'>
                            <div class='sec' style='margin-top:0'>{$secC}. Tenant Sering Terlambat (&ge;2x)</div>
                            <table>
                                <thead><tr>
                                    <th class='c'>No.</th><th>Tenant</th><th>Email</th><th>HP</th>
                                    <th class='c'>Jml</th><th class='c'>Avg hari</th><th class='r'>Total Dibayar</th>
                                </tr></thead>
                                <tbody>{$clRows}</tbody>
                            </table>
                        </div>
                    </div>";
                    $secD = 'D';
                } else {
                    $html .= "<div class='note'><strong>{$secC}. Tenant Sering Terlambat:</strong> Tidak ada tenant yang terlambat &ge;2x pada tahun {$year}.</div>";
                    $secD = 'C';
                }

                $html .= "<div class='note'><strong>Ket kategori:</strong> Ringan = &le;7 hari &bull; Sedang = 8-14 hari &bull; Kritis = &gt;14 hari. Urutan: paling lama terlambat di atas.</div>";
                $html .= "<div class='sec'>{$secD}. Detail Invoice Dibayar Terlambat &mdash; {$year}" . ($lateInvs->count() > 200 ? ' (200 pertama)' : '') . "</div>
                <table>
                    <thead><tr>
                        <th>No. Invoice</th><th>Tenant</th><th class='c'>Kamar</th>
                        <th class='r'>Tagihan (Rp)</th><th class='c'>Jatuh Tempo</th>
                        <th class='c'>Tgl Dibayar</th><th class='c'>Hari Telat</th><th class='c'>Kategori</th>
                    </tr></thead>
                    <tbody>{$ltRows}</tbody>
                </table>
                {$ltMore}";

                $html .= $ftr;
                $filename = "urban-kost-laporan-keterlambatan-{$year}.pdf";
                break;

            // =================================================================
            // TIKET
            // KPI → 2-col (Per Kategori | Prioritas + Tren) → Urgent → Detail Tiket
            // =================================================================
            case 'tiket':
                $totalTk    = Ticket::whereYear('created_at',$year)->count();
                $resolvedTk = Ticket::where('status','resolved')->whereYear('created_at',$year)->count();
                $openTk     = Ticket::whereIn('status',['open','in_progress'])->count();
                $urgentTk   = Ticket::where('priority','urgent')->whereIn('status',['open','in_progress'])->count();
                $avgMttrTk  = $this->calcAvgMttr($year);
                $avgRatTk   = round(Ticket::whereYear('created_at',$year)->whereNotNull('rating')->avg('rating') ?? 0, 1);
                $resoRate   = $pct($resolvedTk, $totalTk);

                // Per Kategori
                $catData = Ticket::selectRaw('category, COUNT(*) as count, AVG(rating) as avg_rating, SUM(CASE WHEN status="resolved" THEN 1 ELSE 0 END) as resolved_count, SUM(CASE WHEN priority="urgent" THEN 1 ELSE 0 END) as urgent_count, SUM(CASE WHEN status IN ("open","in_progress") THEN 1 ELSE 0 END) as open_count')
                    ->whereYear('created_at',$year)->groupBy('category')->get();
                $catRows = '';
                foreach ($catData as $cd) {
                    $pctCat  = $pct($cd->resolved_count, $cd->count);
                    $perfCat = $pctCat >= 80 ? 'Baik' : ($pctCat >= 60 ? 'Perhatian' : 'Perlu Perbaikan');
                    $cls     = $pctCat < 60 && $cd->count > 0 ? 'hi-dk' : '';
                    $catRows .= "<tr class='{$cls}'>
                        <td class='b'>" . ucfirst($cd->category) . "</td>
                        <td class='c'>{$cd->count}</td><td class='c b'>{$cd->resolved_count}</td>
                        <td class='c'>{$cd->open_count}</td><td class='c'>{$cd->urgent_count}</td>
                        <td class='c b'>{$pctCat}%</td>
                        <td class='c sm'>" . round($cd->avg_rating??0,1) . "/5</td>
                        <td class='c sm'>{$perfCat}</td>
                    </tr>";
                }

                // Prioritas
                $prData = Ticket::selectRaw('priority, COUNT(*) as count')->whereYear('created_at',$year)->groupBy('priority')->get()->keyBy('priority');
                $prRows = '';
                foreach ([['urgent','Urgent'],['high','Tinggi'],['normal','Normal'],['low','Rendah']] as [$k,$l]) {
                    $cnt = $prData->get($k)?->count ?? 0;
                    $prRows .= "<tr><td class='b'>{$l}</td><td class='c b' style='font-size:11px'>{$cnt}</td></tr>";
                }

                // Tren bulanan
                $tkMonRows = '';
                for ($m = 1; $m <= 12; $m++) {
                    $tot = Ticket::whereYear('created_at',$year)->whereMonth('created_at',$m)->count();
                    $res = Ticket::where('status','resolved')->whereYear('created_at',$year)->whereMonth('created_at',$m)->count();
                    $opn = Ticket::whereIn('status',['open','in_progress'])->whereYear('created_at',$year)->whereMonth('created_at',$m)->count();
                    $urg = Ticket::where('priority','urgent')->whereYear('created_at',$year)->whereMonth('created_at',$m)->count();
                    $p   = $pct($res,$tot);
                    $stat = $p >= 80 ? 'Baik' : ($p >= 60 ? 'Sedang' : ($tot > 0 ? 'Perhatian' : '-'));
                    $cls  = $p < 60 && $tot > 0 ? 'hi-dk' : '';
                    $tkMonRows .= "<tr class='{$cls}'>
                        <td>" . Carbon::create($year,$m,1)->format('M Y') . "</td>
                        <td class='c'>{$tot}</td><td class='c b'>{$res}</td>
                        <td class='c'>{$opn}</td><td class='c'>{$urg}</td>
                        <td class='c b'>{$p}%</td><td class='c sm'>{$stat}</td>
                    </tr>";
                }

                // Urgent masih terbuka
                $urgTks = Ticket::with(['user:id,name','assignee:id,name'])
                    ->where('priority','urgent')->whereIn('status',['open','in_progress'])->orderBy('created_at')->get();
                $urgRows = '';
                foreach ($urgTks as $ut) {
                    $urgRows .= "<tr class='hi-rd'>
                        <td class='mn sm'>{$ut->ticket_number}</td>
                        <td class='b'>" . htmlspecialchars($ut->user?->name ?? '-') . "</td>
                        <td>" . htmlspecialchars($ut->subject) . "</td>
                        <td class='c sm'>" . ucfirst($ut->category) . "</td>
                        <td class='c sm'>" . ucfirst($ut->status) . "</td>
                        <td class='sm'>" . ($ut->assignee?->name ?? '-- Belum ada PIC --') . "</td>
                        <td class='c sm'>{$ut->created_at->format('d/m/Y ')}</td>
                        <td class='sm'>{$ut->created_at->diffForHumans()}</td>
                    </tr>";
                }

                // Detail tiket
                $tickets = Ticket::with(['user:id,name','assignee:id,name'])->whereYear('created_at',$year)->orderByDesc('created_at')->get();
                $tkRows = '';
                foreach ($tickets->take(200) as $tk) {
                    $mttr  = $tk->resolved_at ? round($tk->created_at->diffInMinutes($tk->resolved_at)/60,1).'j' : '—';
                    $prLbl = match ($tk->priority??'normal') {'urgent'=>'Urgent','high'=>'Tinggi','normal'=>'Normal','low'=>'Rendah',default=>ucfirst($tk->priority??'-')};
                    $stLbl = match ($tk->status) {'open'=>'Terbuka','in_progress'=>'Proses','pending'=>'Pending','resolved'=>'Selesai','closed'=>'Tutup',default=>ucfirst($tk->status)};
                    $cls   = ($tk->priority??'') === 'urgent' ? 'hi-rd' : '';
                    $tkRows .= "<tr class='{$cls}'>
                        <td class='mn sm'>{$tk->ticket_number}</td>
                        <td class='b'>" . htmlspecialchars($tk->user?->name ?? '-') . "</td>
                        <td>" . htmlspecialchars(\Str::limit($tk->subject, 32)) . "</td>
                        <td class='c sm'>" . ucfirst($tk->category) . "</td>
                        <td class='c b'>{$prLbl}</td>
                        <td class='c b'>{$stLbl}</td>
                        <td class='sm'>" . ($tk->assignee?->name ?? '-') . "</td>
                        <td class='c sm'>{$tk->created_at->format('d/m/Y')}</td>
                        <td class='c sm'>" . ($tk->resolved_at?->format('d/m/Y') ?? '—') . "</td>
                        <td class='c'>{$mttr}</td>
                        <td class='c'>" . ($tk->rating ? $tk->rating.'/5' : '—') . "</td>
                    </tr>";
                }
                $tkMore = $tickets->count() > 200
                    ? "<div class='note'>... dan <strong>" . ($tickets->count()-200) . " tiket</strong> lainnya. Gunakan export Excel untuk data lengkap.</div>"
                    : '';

                $html .= $hdr('LAPORAN KELUHAN &amp; PERFORMA TIKET LAYANAN', 'Rekap per kategori, prioritas, tren &amp; detail &mdash; Tahun ' . $year);

                // KPI
                $html .= "<div class='kpi'>
                    <div class='kpi-hd'>Ringkasan Layanan Tiket &mdash; Tahun {$year}</div>
                    <div class='kpi-row'>
                        <div class='kpi-cell'><div class='kpi-val'>{$totalTk}</div><div class='kpi-lbl'>Total Tiket {$year}</div></div>
                        <div class='kpi-cell'><div class='kpi-val'>{$resolvedTk} ({$resoRate}%)</div><div class='kpi-lbl'>Terselesaikan</div></div>
                        <div class='kpi-cell'><div class='kpi-val'>{$openTk}</div><div class='kpi-lbl'>Masih Terbuka</div></div>
                        <div class='kpi-cell'><div class='kpi-val'>{$urgentTk}</div><div class='kpi-lbl'>Urgent Belum Selesai</div></div>
                        <div class='kpi-cell'><div class='kpi-val'>{$avgMttrTk} jam</div><div class='kpi-lbl'>Avg MTTR (&lt;24j target)</div></div>
                        <div class='kpi-cell'><div class='kpi-val'>{$avgRatTk}/5</div><div class='kpi-lbl'>Avg Rating</div></div>
                    </div>
                </div>
                <div class='note'><strong>Target:</strong> Penyelesaian &gt;80% &bull; MTTR &lt;24 jam (urgent &lt;4 jam). Baris gelap = kategori/bulan perlu perhatian.</div>";

                // 2-col: per kategori + (prioritas + tren)
                $html .= "<div class='two-col'>
                    <div class='col-l'>
                        <div class='sec' style='margin-top:0'>A. Rekap Per Kategori &mdash; {$year}</div>
                        <table>
                            <thead><tr>
                                <th>Kategori</th><th class='c'>Total</th><th class='c'>Selesai</th>
                                <th class='c'>Terbuka</th><th class='c'>Urgent</th>
                                <th class='c'>% Selesai</th><th class='c'>Rating</th><th class='c'>Kinerja</th>
                            </tr></thead>
                            <tbody>{$catRows}</tbody>
                        </table>
                    </div>
                    <div class='col-r'>
                        <div class='sec' style='margin-top:0'>B. Distribusi Prioritas &mdash; {$year}</div>
                        <table>
                            <thead><tr><th>Prioritas</th><th class='c'>Jumlah Tiket</th></tr></thead>
                            <tbody>{$prRows}</tbody>
                        </table>

                        <div class='sec'>C. Tren Tiket Bulanan &mdash; {$year}</div>
                        <table>
                            <thead><tr>
                                <th>Bulan</th><th class='c'>Total</th><th class='c'>✓</th>
                                <th class='c'>Terbuka</th><th class='c'>Urgent</th><th class='c'>%</th><th class='c'>Status</th>
                            </tr></thead>
                            <tbody>{$tkMonRows}</tbody>
                        </table>
                    </div>
                </div>";

                // Urgent + Detail → halaman 2
                $html .= $pb;
                $html .= $hdr('LAPORAN KELUHAN &amp; TIKET LAYANAN — Halaman 2', 'Tiket urgent masih terbuka &amp; detail semua tiket &mdash; Tahun ' . $year);

                // Urgent
                if ($urgTks->isNotEmpty()) {
                    $html .= "<div class='sec'>D. Tiket Urgent Masih Terbuka &mdash; Perlu Penanganan Segera ({$urgTks->count()} tiket)</div>
                    <table>
                        <thead><tr>
                            <th>No. Tiket</th><th>Tenant</th><th>Subjek</th><th class='c'>Kat.</th>
                            <th class='c'>Status</th><th>PIC</th><th class='c'>Dibuat</th><th>Sudah Menunggu</th>
                        </tr></thead>
                        <tbody>{$urgRows}</tbody>
                    </table>";
                    $secE = 'E';
                } else {
                    $html .= "<div class='note'><strong>D. Tiket Urgent:</strong> Tidak ada tiket urgent yang masih terbuka saat ini.</div>";
                    $secE = 'D';
                }

                // Detail
                $html .= "<div class='note'><strong>Ket:</strong> Baris merah muda = tiket urgent &bull; MTTR = jam penyelesaian.</div>";
                $html .= "<div class='sec'>{$secE}. Detail Semua Tiket &mdash; {$year}" . ($tickets->count() > 200 ? ' (200 pertama)' : '') . "</div>
                <table>
                    <thead><tr>
                        <th>No. Tiket</th><th>Tenant</th><th>Subjek</th><th class='c'>Kat.</th>
                        <th class='c'>Prioritas</th><th class='c'>Status</th><th>PIC</th>
                        <th class='c'>Dibuat</th><th class='c'>Selesai</th><th class='c'>MTTR</th><th class='c'>Rating</th>
                    </tr></thead>
                    <tbody>{$tkRows}</tbody>
                </table>
                {$tkMore}";

                $html .= $ftr;
                $filename = "urban-kost-laporan-tiket-{$year}.pdf";
                break;

            default:
                abort(404, "Section tidak dikenali: {$section}");
        }

        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont'          => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => false,
                'dpi'                  => 96,
                'margin_top'           => 0,
                'margin_bottom'        => 0,
                'margin_left'          => 0,
                'margin_right'         => 0,
            ]);

        return $pdf->download($filename);
    }
    // =========================================================================
    // PAJAK PPh FINAL 0,5% — PP No. 55 Tahun 2022
    //
    // LOGIKA PERHITUNGAN (PENTING — basis TAHUNAN, bukan bulanan):
    // - Threshold Rp 500.000.000/tahun HANYA berlaku untuk WP Orang Pribadi,
    //   dan dihitung dari AKUMULASI OMZET SETAHUN PENUH (1 Jan s.d. 31 Des),
    //   bukan dipotong-potong / direset setiap bulan.
    // - WP Orang Pribadi:
    //     * Omzet tahunan <= Rp 500jt  -> BEBAS PPh Final (Rp 0)
    //     * Omzet tahunan >  Rp 500jt  -> PPh = 0,5% x (Omzet Tahunan - Rp 500jt)
    // - WP Badan (PT/CV/dll): TIDAK ada pengecualian Rp 500jt.
    //     * PPh = 0,5% x Omzet Tahunan (berlaku dari rupiah pertama, s.d. Rp 4,8 M)
    // - Urban Kost adalah properti milik pribadi -> kategori yang relevan
    //   adalah WP Orang Pribadi, sehingga default tampilan adalah WP OP.
    // - Pelaporan yang sesuai adalah SPT TAHUNAN (basis 1 tahun pajak),
    //   bukan SPT Masa bulanan, karena perhitungan & threshold bersifat tahunan.
    // =========================================================================

    /**
     * Hitung omzet tahunan (akumulasi invoice lunas, periode 1 Jan - 31 Des tahun terkait).
     */
    private function calcOmzetTahunan(int $year): float
    {
        return (float) Invoice::where('status', 'paid')
            ->whereYear('period_start', $year)
            ->sum('total_amount');
    }

    /**
     * Hitung PPh Final 0,5% berdasarkan jenis WP, dari OMZET TAHUNAN (bukan bulanan).
     *
     * @return array{omzet: float, dasar_kena_pajak: float, pph: float, bebas: bool, tarif: float}
     */
    private function calcPphFinal(float $omzetTahunan, string $wpType): array
    {
        if ($wpType === 'op') {
            // WP Orang Pribadi: bebas s.d. Rp 500jt, 0,5% hanya pada porsi kelebihan
            $dasarKenaPajak = max(0, $omzetTahunan - self::TAX_THRESHOLD_OP);
            $pph   = round($dasarKenaPajak * self::TAX_RATE, 0);
            $bebas = $omzetTahunan <= self::TAX_THRESHOLD_OP;
        } else {
            // WP Badan: 0,5% dari rupiah pertama, tidak ada pengecualian
            $dasarKenaPajak = $omzetTahunan;
            $pph   = round($omzetTahunan * self::TAX_RATE, 0);
            $bebas = $omzetTahunan <= 0;
        }

        return [
            'omzet'            => $omzetTahunan,
            'dasar_kena_pajak' => $dasarKenaPajak,
            'pph'              => $pph,
            'bebas'            => $bebas,
            'tarif'            => self::TAX_RATE * 100,
            'melebihi_umkm'    => $omzetTahunan > self::TAX_UMKM_CEILING,
        ];
    }

    /**
     * Halaman Monitoring Pajak — basis TAHUNAN.
     * Menampilkan omzet tahun berjalan dan status terhadap batas bebas pajak WP Orang Pribadi.
     */
    public function tax(Request $request)
    {
        $year  = (int)($request->year ?? now()->year);
        $years = range(now()->year, now()->year - 4, -1);

        // --- Omzet tahun yang dipilih (akumulasi penuh 1 Jan - 31 Des) ---
        $omzetTahunIni = $this->calcOmzetTahunan($year);
        $hitungOP      = $this->calcPphFinal($omzetTahunIni, 'op');

        $tpct      = min(round(($omzetTahunIni / self::TAX_THRESHOLD_OP) * 100, 1), 100);
        $remaining = max(0, self::TAX_THRESHOLD_OP - $omzetTahunIni);

        // --- Rincian omzet per bulan, HANYA untuk visualisasi tren (bukan basis pajak) ---
        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $rev = (float)Invoice::where('status', 'paid')
                ->whereYear('period_start', $year)
                ->whereMonth('period_start', $m)
                ->sum('total_amount');

            $monthlyData[] = [
                'month'        => Carbon::create($year, $m, 1)->format('F Y'),
                'month_short'  => Carbon::create($year, $m, 1)->format('M'),
                'revenue'      => $rev,
                'paid_count'   => Invoice::where('status', 'paid')->whereYear('period_start', $year)->whereMonth('period_start', $m)->count(),
                'unpaid_count' => Invoice::whereIn('status', ['unpaid', 'overdue'])->whereYear('period_start', $year)->whereMonth('period_start', $m)->count(),
            ];
        }

        // --- Histori omzet tahunan 5 tahun terakhir + status tiap tahun ---
        $historiTahunan = [];
        foreach ($years as $y) {
            $omzetY = $this->calcOmzetTahunan($y);
            $opY    = $this->calcPphFinal($omzetY, 'op');
            $historiTahunan[] = [
                'year'       => $y,
                'omzet'      => $omzetY,
                'tpct'       => min(round(($omzetY / self::TAX_THRESHOLD_OP) * 100, 1), 100),
                'pph_op'     => $opY['pph'],
                'bebas_op'   => $opY['bebas'],
                'is_current' => $y === $year,
            ];
        }

        // --- Omzet bulan ini (informasi arus kas, bukan basis pajak) ---
        $curRev   = (float)Invoice::where('status', 'paid')->whereYear('period_start', $year)->whereMonth('period_start', now()->month)->sum('total_amount');
        $curCount = (int)Invoice::where('status', 'paid')->whereYear('period_start', $year)->whereMonth('period_start', now()->month)->count();

        // --- Deadline pelaporan SPT TAHUNAN: 31 Maret tahun berikutnya (WP OP) ---
        $taxDeadline = Carbon::create($year + 1, 3, 31)->format('d F Y');

        $revenueByType = Invoice::where('status', 'paid')
            ->whereYear('period_start', $year)
            ->select('invoice_type', DB::raw('SUM(total_amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('invoice_type')->get();

        return view('owner.tax.index', compact(
            'year', 'years',
            'omzetTahunIni', 'hitungOP',
            'tpct', 'remaining',
            'monthlyData', 'historiTahunan',
            'curRev', 'curCount', 'taxDeadline',
            'revenueByType'
        ));
    }

    public function exportTax(Request $request)
    {
        $year   = (int)($request->year ?? now()->year);
        $format = $request->get('format', 'excel');

        return $format === 'pdf'
            ? $this->exportTaxAsPdf($year, 'op')
            : $this->exportTaxAsExcel($year, 'op');
    }

    /**
     * Export PDF — Formulir SPT Tahunan PPh Final 0,5% (basis tahunan, mengikuti
     * struktur resmi F.1.1.32.04 sebagai referensi tata letak, disesuaikan untuk
     * pelaporan TAHUNAN bukan bulanan, karena dasar hukum PP 55/2022 mengatur
     * threshold dan tarif berdasarkan akumulasi omzet setahun).
     */
    /**
     * Export PDF — mengikuti struktur resmi Formulir F.1.1.32.04
     * (SPT Masa PPh Final Pasal 4 Ayat 2 — PER-53/PJ/2009), dengan judul
     * disesuaikan menjadi "SPT Tahunan" karena PP 55/2022 mengatur threshold
     * dan tarif berdasarkan akumulasi omzet TAHUNAN, bukan per masa/bulan.
     *
     * Bagian B mengikuti urutan 11 baris resmi formulir asli. Hanya baris 11.a
     * (Penghasilan Tertentu Lainnya — usaha kos) yang diisi data nyata; baris
     * lain yang tidak relevan untuk usaha kos dikosongkan (strip "—").
     *
     * Hanya untuk WP Orang Pribadi — sesuai status Urban Kost sebagai properti
     * milik pribadi.
     */
    private function exportTaxAsPdf(int $year, string $wpType): \Symfony\Component\HttpFoundation\Response
    {
        $printDate = now()->format('d F Y');

        $omzetTahunan = $this->calcOmzetTahunan($year);
        $hitung       = $this->calcPphFinal($omzetTahunan, 'op');
        $tpct         = min(round(($omzetTahunan / self::TAX_THRESHOLD_OP) * 100, 1), 100);

        $wpName    = config('tax.wp_name',    env('TAX_WP_NAME',    'URBAN KOST'));
        $wpNpwp    = config('tax.wp_npwp',    env('TAX_WP_NPWP',    '00.000.000.0-000.000'));
        $wpAddress = config('tax.wp_address', env('TAX_WP_ADDRESS', 'Yogyakarta'));

        $taxDeadline = Carbon::create($year + 1, 3, 31)->format('d F Y');
        $taxInt      = (int)$hitung['pph'];
        $terbilang   = $taxInt > 0 ? $this->terbilangRupiah($taxInt) . ' Rupiah' : 'Nihil';

        $nilaiObjek = number_format($hitung['dasar_kena_pajak'], 0, ',', '.');
        $pphSetor   = number_format($hitung['pph'], 0, ',', '.');

        // ── CSS — mengikuti proporsi formulir resmi (kolom (1)-(5), border tegas) ──
        $css = <<<'CSS'
        <style>
        * { box-sizing:border-box; margin:0; padding:0; }
        body { font-family:'DejaVu Sans',Arial,sans-serif; font-size:7.3px; color:#000; background:#fff; line-height:1.35; }
        .page { margin:16px 20px; }

        .gov-header { display:table; width:100%; border:1.5px solid #000; margin-bottom:0; }
        .gov-left   { display:table-cell; vertical-align:middle; width:20%; padding:7px 9px; border-right:1px solid #000; text-align:center; }
        .gov-mid    { display:table-cell; vertical-align:middle; text-align:center; padding:7px 9px; }
        .gov-right  { display:table-cell; vertical-align:middle; width:22%; padding:7px 9px; border-left:1px solid #000; text-align:center; }
        .gov-org    { font-size:6.3px; font-weight:bold; text-transform:uppercase; letter-spacing:.3px; }
        .gov-title  { font-size:9.5px; font-weight:bold; margin-top:3px; }
        .gov-sub    { font-size:6.3px; margin-top:3px; color:#333; }
        .spt-box    { display:inline-block; border:1px solid #000; padding:2px 7px; font-size:6.2px; font-weight:bold; margin:1px 1px 0 0; }
        .masa-row   { margin-top:5px; font-weight:bold; font-size:6.3px; }
        .masa-box   { display:inline-block; border:1px solid #000; width:18px; height:13px; text-align:center; line-height:13px; font-size:7px; margin:0 1px; font-weight:bold; }

        .ident-table { width:100%; border-collapse:collapse; border:1px solid #000; border-top:none; }
        .ident-table td { border:1px solid #000; padding:3px 5px; font-size:7.3px; vertical-align:middle; }
        .ident-table td.no  { width:2.5%; text-align:center; }
        .ident-table td.lbl { width:14%; }
        .ident-table td.sep { width:1.5%; text-align:center; }
        .npwp-cell  { letter-spacing:2px; font-family:'DejaVu Sans Mono',monospace; font-size:7.3px; }

        .section-bar { background:#fff; border:1px solid #000; border-top:none; padding:3px 6px; font-weight:bold; font-size:7.3px; }

        .obj-table { width:100%; border-collapse:collapse; border:1.5px solid #000; margin-top:0; }
        .obj-table th { border:1px solid #000; padding:3px 4px; font-size:6.5px; font-weight:bold; text-align:center; background:#fff; }
        .obj-table td { border:1px solid #000; padding:2.5px 5px; font-size:7px; vertical-align:middle; }
        .obj-table .uraian-l1 { font-weight:bold; }
        .obj-table .uraian-l2 { padding-left:10px; }
        .obj-table .uraian-l3 { padding-left:18px; color:#444; }
        .obj-table .num-col   { text-align:center; }
        .obj-table .val-col   { text-align:right; }
        .obj-table .dim       { color:#888; }
        .highlight-row td     { background:#fafafa; }
        .jumlah-row td        { font-weight:bold; border-top:1.5px solid #000; }
        .terbilang-row td     { font-size:6.8px; padding:4px 6px; }

        .lampiran-table { width:100%; border-collapse:collapse; border:1px solid #000; border-top:none; }
        .lampiran-table td { border:1px solid #000; padding:3px 5px; font-size:7.3px; vertical-align:middle; }
        .lampiran-table td.chk { width:2.5%; text-align:center; }

        .sign-table { width:100%; border-collapse:collapse; border:1px solid #000; border-top:none; }
        .sign-table td { border:1px solid #000; padding:5px 7px; font-size:7px; vertical-align:top; }
        .sign-box { border:1px solid #000; height:42px; margin-top:4px; }
        .petugas-cell { text-align:center; font-size:6.7px; }

        .footer-note { font-size:5.8px; color:#444; margin-top:4px; }

        .note-box { border:1px solid #ccc; border-left:3px solid #555; background:#fafafa; padding:5px 8px; font-size:6.6px; color:#333; margin:6px 0; line-height:1.55; }
        .alert-box { border:1.5px solid; padding:6px 9px; font-size:7.3px; line-height:1.6; margin:6px 0; }
        .alert-safe { border-color:#2a7a42; background:#f0fbf4; color:#1a5a2f; }
        .alert-warn { border-color:#a07020; background:#fdf8ec; color:#5a3d00; }

        .sum-table { width:100%; border-collapse:collapse; border:1.5px solid #000; margin-top:0; }
        .sum-table th { background:#eee; border:1px solid #000; padding:4px 5px; font-size:6.6px; font-weight:bold; text-align:center; }
        .sum-table td { border:1px solid #000; padding:3.5px 6px; font-size:7.2px; }
        .sum-table tfoot td { font-weight:bold; }
        .tr { text-align:right; } .tc { text-align:center; } .tb { font-weight:bold; }
        </style>
        CSS;

        $masaTahun = (string)$year;

        $html = $css . "<div class='page'>
            <div class='gov-header'>
                <div class='gov-left'>
                    <div class='gov-org'>Kementerian<br>Keuangan R.I.</div>
                    <div style='margin-top:4px;font-size:5.5px;'>DIREKTORAT JENDERAL PAJAK</div>
                </div>
                <div class='gov-mid'>
                    <div class='gov-title'>SURAT PEMBERITAHUAN (SPT) TAHUNAN</div>
                    <div class='gov-title'>PAJAK PENGHASILAN FINAL PASAL 4 AYAT (2)</div>
                    <div class='gov-sub'>Mengacu pada Formulir F.1.1.32.04 &mdash; disesuaikan untuk pelaporan tahunan sesuai PP No. 55 Tahun 2022</div>
                </div>
                <div class='gov-right'>
                    <div><span class='spt-box'>&#10003; SPT Normal</span></div>
                    <div style='margin-top:3px'><span class='spt-box'>&#9633; SPT Pembetulan Ke-___</span></div>
                    <div class='masa-row'>Tahun Pajak</div>
                    <div style='margin-top:3px'><span class='masa-box' style='width:50px'>{$masaTahun}</span></div>
                </div>
            </div>

            <div class='section-bar'>BAGIAN A. IDENTITAS WAJIB PAJAK</div>
            <table class='ident-table'>
                <tr><td class='no'>1.</td><td class='lbl'>NPWP</td><td class='sep'>:</td><td class='npwp-cell'>{$wpNpwp}</td></tr>
                <tr><td class='no'>2.</td><td class='lbl'>Nama</td><td class='sep'>:</td><td class='tb'>{$wpName}</td></tr>
                <tr><td class='no'>3.</td><td class='lbl'>Alamat</td><td class='sep'>:</td><td>{$wpAddress}</td></tr>
            </table>

            <div class='section-bar'>BAGIAN B. OBJEK PAJAK</div>
            <table class='obj-table'>
                <thead>
                    <tr>
                        <th style='width:46%'>Uraian</th>
                        <th style='width:10%'>KAP/KJS</th>
                        <th style='width:18%'>Nilai Objek Pajak (Rp)</th>
                        <th style='width:8%'>Tarif (%)</th>
                        <th style='width:18%'>PPh yang Dipotong/Dipungut/Disetor Sendiri (Rp)</th>
                    </tr>
                    <tr>
                        <th>(1)</th><th>(2)</th><th>(3)</th><th>(4)</th><th>(5)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td class='uraian-l1'>1. Bunga Deposito/Tabungan, Diskonto SBI dan Jasa Giro</td><td colspan='4'></td></tr>
                    <tr><td class='uraian-l2'>a. Bunga Deposito/Tabungan &mdash; 1) Dalam Negeri</td><td class='num-col dim'>411128/404</td><td class='val-col dim'>&mdash;</td><td class='num-col dim'>&mdash;</td><td class='val-col dim'>&mdash;</td></tr>
                    <tr><td class='uraian-l3'>2) Luar Negeri</td><td class='num-col dim'>411128/404</td><td class='val-col dim'>&mdash;</td><td class='num-col dim'>&mdash;</td><td class='val-col dim'>&mdash;</td></tr>
                    <tr><td class='uraian-l2'>b. Diskonto Sertifikat Bank Indonesia</td><td class='num-col dim'>411128/404</td><td class='val-col dim'>&mdash;</td><td class='num-col dim'>&mdash;</td><td class='val-col dim'>&mdash;</td></tr>
                    <tr><td class='uraian-l2'>c. Jasa Giro</td><td class='num-col dim'>411128/404</td><td class='val-col dim'>&mdash;</td><td class='num-col dim'>&mdash;</td><td class='val-col dim'>&mdash;</td></tr>

                    <tr><td class='uraian-l1'>2. Transaksi Penjualan Saham</td><td colspan='4'></td></tr>
                    <tr><td class='uraian-l2'>a. Saham Pendiri</td><td class='num-col dim'>411128/407</td><td class='val-col dim'>&mdash;</td><td class='num-col dim'>&mdash;</td><td class='val-col dim'>&mdash;</td></tr>
                    <tr><td class='uraian-l2'>b. Bukan Saham Pendiri</td><td class='num-col dim'>411128/406</td><td class='val-col dim'>&mdash;</td><td class='num-col dim'>&mdash;</td><td class='val-col dim'>&mdash;</td></tr>

                    <tr><td class='uraian-l1'>3. Bunga/Diskonto Obligasi dan Surat Berharga Negara</td><td class='num-col dim'>411128/401</td><td class='val-col dim'>&mdash;</td><td class='num-col dim'>&mdash;</td><td class='val-col dim'>&mdash;</td></tr>
                    <tr><td class='uraian-l1'>4. Hadiah Undian</td><td class='num-col dim'>411128/405</td><td class='val-col dim'>&mdash;</td><td class='num-col dim'>&mdash;</td><td class='val-col dim'>&mdash;</td></tr>

                    <tr><td class='uraian-l1'>5. Persewaan Tanah dan/atau Bangunan</td><td colspan='4'></td></tr>
                    <tr><td class='uraian-l2'>a. Penyewa sebagai Pemotong Pajak</td><td class='num-col dim'>411128/403</td><td class='val-col dim'>&mdash;</td><td class='num-col dim'>&mdash;</td><td class='val-col dim'>&mdash;</td></tr>
                    <tr><td class='uraian-l2'>b. Orang Pribadi/Badan yang Menyetor Sendiri PPh</td><td class='num-col dim'>411128/403</td><td class='val-col dim'>&mdash;</td><td class='num-col dim'>&mdash;</td><td class='val-col dim'>&mdash;</td></tr>

                    <tr><td class='uraian-l1'>6. Jasa Konstruksi</td><td colspan='4'></td></tr>
                    <tr><td class='uraian-l2'>a. Perencana Konstruksi</td><td class='num-col dim'>411128/409</td><td class='val-col dim'>&mdash;</td><td class='num-col dim'>&mdash;</td><td class='val-col dim'>&mdash;</td></tr>
                    <tr><td class='uraian-l2'>b. Pelaksana Konstruksi</td><td class='num-col dim'>411128/409</td><td class='val-col dim'>&mdash;</td><td class='num-col dim'>&mdash;</td><td class='val-col dim'>&mdash;</td></tr>
                    <tr><td class='uraian-l2'>c. Pengawas Konstruksi</td><td class='num-col dim'>411128/409</td><td class='val-col dim'>&mdash;</td><td class='num-col dim'>&mdash;</td><td class='val-col dim'>&mdash;</td></tr>

                    <tr><td class='uraian-l1'>7. Pengalihan Hak atas Tanah/Bangunan</td><td class='num-col dim'>411128/402</td><td class='val-col dim'>&mdash;</td><td class='num-col dim'>&mdash;</td><td class='val-col dim'>&mdash;</td></tr>
                    <tr><td class='uraian-l1'>8. Bunga Simpanan Koperasi kepada Anggota WP OP</td><td class='num-col dim'>411128/417</td><td class='val-col dim'>&mdash;</td><td class='num-col dim'>&mdash;</td><td class='val-col dim'>&mdash;</td></tr>
                    <tr><td class='uraian-l1'>9. Transaksi Derivatif Kontrak Berjangka di Bursa</td><td class='num-col dim'>411128/418</td><td class='val-col dim'>&mdash;</td><td class='num-col dim'>&mdash;</td><td class='val-col dim'>&mdash;</td></tr>
                    <tr><td class='uraian-l1'>10. Dividen WP Orang Pribadi Dalam Negeri</td><td class='num-col dim'>411128/419</td><td class='val-col dim'>&mdash;</td><td class='num-col dim'>&mdash;</td><td class='val-col dim'>&mdash;</td></tr>

                    <tr><td class='uraian-l1'>11. Penghasilan Tertentu Lainnya</td><td colspan='4'></td></tr>
                    <tr class='highlight-row'>
                        <td class='uraian-l2 tb'>a. Penghasilan Usaha Jasa Penyewaan Kamar Kos (UMKM &mdash; PP No. 55 Tahun 2022)</td>
                        <td class='num-col tb'>411128/420</td>
                        <td class='val-col tb'>Rp {$nilaiObjek}</td>
                        <td class='num-col tb'>0,5</td>
                        <td class='val-col tb'>Rp {$pphSetor}</td>
                    </tr>
                    <tr><td class='uraian-l2 dim'>b. &hellip;</td><td class='num-col dim'>&mdash;</td><td class='val-col dim'>&mdash;</td><td class='num-col dim'>&mdash;</td><td class='val-col dim'>&mdash;</td></tr>
                    <tr><td class='uraian-l2 dim'>c. &hellip;</td><td class='num-col dim'>&mdash;</td><td class='val-col dim'>&mdash;</td><td class='num-col dim'>&mdash;</td><td class='val-col dim'>&mdash;</td></tr>

                    <tr class='jumlah-row'>
                        <td colspan='2' class='tc'>JUMLAH</td>
                        <td class='val-col'>Rp {$nilaiObjek}</td>
                        <td class='num-col'>0,5</td>
                        <td class='val-col'>Rp {$pphSetor}</td>
                    </tr>
                    <tr class='terbilang-row'>
                        <td colspan='5'><strong>Terbilang:</strong> {$terbilang}</td>
                    </tr>
                </tbody>
            </table>

            <div class='note-box'><strong>Catatan:</strong> Nilai Objek Pajak pada baris 11.a adalah porsi omzet tahun {$year} di atas batas bebas pajak Wajib Pajak Orang Pribadi (Rp 500.000.000), sesuai PP No. 55 Tahun 2022 Pasal 7 ayat (2a). Baris lain pada Bagian B tidak relevan untuk usaha penyewaan kamar kos sehingga dikosongkan.</div>

            " . ($hitung['bebas']
                ? "<div class='alert-box alert-safe'><strong>Status: Bebas PPh Final.</strong> Omzet tahun {$year} sebesar Rp " . number_format($omzetTahunan, 0, ',', '.') . " ({$tpct}% dari batas) masih di bawah atau sama dengan Rp 500.000.000, sehingga tidak ada PPh Final yang harus dibayar.</div>"
                : "<div class='alert-box alert-warn'><strong>PPh Final terutang.</strong> Omzet tahun {$year} sebesar Rp " . number_format($omzetTahunan, 0, ',', '.') . " ({$tpct}% dari batas) telah melebihi Rp 500.000.000. PPh Final 0,5% dikenakan atas kelebihan tersebut.</div>") . "

            <div class='section-bar'>BAGIAN C. LAMPIRAN</div>
            <table class='lampiran-table'>
                <tr><td class='chk'>&#9745;</td><td>Rincian omzet bulanan tahun {$year} (lampiran pendukung internal)</td></tr>
                <tr><td class='chk'>&#9633;</td><td>Surat Setoran Pajak (SSP) / Bukti Penerimaan Negara (BPN)</td></tr>
                <tr><td class='chk'>&#9633;</td><td>Surat Kuasa Khusus (bila SPT ditandatangani oleh kuasa)</td></tr>
            </table>

            <div class='section-bar'>BAGIAN D. PERNYATAAN DAN TANDA TANGAN</div>
            <table class='sign-table'>
                <tr>
                    <td colspan='2' style='font-size:6.8px;line-height:1.5'>
                        Dengan menyadari sepenuhnya akan segala akibatnya termasuk sanksi-sanksi sesuai dengan ketentuan perundang-undangan yang berlaku,
                        saya menyatakan bahwa apa yang telah saya beritahukan di atas beserta lampiran-lampirannya adalah <strong>benar, lengkap dan jelas</strong>.
                    </td>
                    <td rowspan='2' class='petugas-cell' style='width:26%'>
                        <strong>Diisi Oleh Petugas</strong>
                        <div class='sign-box'></div>
                        <div style='margin-top:3px'>Tanggal: ___ / ___ / ___</div>
                    </td>
                </tr>
                <tr>
                    <td style='width:37%;text-align:center;font-weight:bold'>PEMOTONG PAJAK / PIMPINAN</td>
                    <td style='width:37%;text-align:center;font-weight:bold'>KUASA WAJIB PAJAK</td>
                </tr>
                <tr>
                    <td>
                        Nama: {$wpName}<br>NPWP: <span class='npwp-cell'>{$wpNpwp}</span>
                        <div class='sign-box'></div>
                        <div style='margin-top:3px'>Tanda Tangan &amp; Cap &nbsp;&nbsp; Tanggal: ___ / ___ / {$year}</div>
                    </td>
                    <td>
                        Nama: ________________________<br>NPWP: ________________________
                        <div class='sign-box'></div>
                        <div style='margin-top:3px'>Tanda Tangan &nbsp;&nbsp; Tanggal: ___ / ___ / {$year}</div>
                    </td>
                    <td></td>
                </tr>
            </table>

            <div class='footer-note'>
                F.1.1.32.04 &mdash; Lampiran I.1 Peraturan Direktur Jenderal Pajak Nomor PER-53/PJ/2009, disesuaikan untuk pelaporan tahunan sesuai PP No. 55 Tahun 2022.
                Kode MAP/KJS: 411128/420. Batas lapor SPT Tahunan: {$taxDeadline}. Dokumen ini bersifat internal, bukan pengganti SPT resmi DJP Online.
            </div>
        </div><div style='page-break-after: always;'></div><div class='page'>

            <div class='section-bar' style='border-top:1px solid #000'>LAMPIRAN &mdash; RINCIAN OMZET PER BULAN TAHUN {$year}</div>
            <table class='sum-table'>
                <thead><tr><th style='width:40%'>Bulan</th><th style='width:35%'>Omzet Lunas (Rp)</th><th style='width:25%'>Jumlah Invoice Lunas</th></tr></thead>
                <tbody>";

        $totalInv = 0;
        for ($m = 1; $m <= 12; $m++) {
            $rev = (float)Invoice::where('status', 'paid')->whereYear('period_start', $year)->whereMonth('period_start', $m)->sum('total_amount');
            $cnt = Invoice::where('status', 'paid')->whereYear('period_start', $year)->whereMonth('period_start', $m)->count();
            $totalInv += $cnt;
            $html .= "<tr>
                <td class='tb'>" . Carbon::create($year, $m, 1)->translatedFormat('F Y') . "</td>
                <td class='tr'>" . number_format($rev, 0, ',', '.') . "</td>
                <td class='tc'>{$cnt}</td>
            </tr>";
        }

        $html .= "</tbody>
                <tfoot><tr>
                    <td class='tb'>TOTAL TAHUN {$year}</td>
                    <td class='tr'>" . number_format($omzetTahunan, 0, ',', '.') . "</td>
                    <td class='tc'>{$totalInv}</td>
                </tr></tfoot>
            </table>
            <div class='note-box'>Rincian bulanan ini bersifat lampiran pendukung internal. Perhitungan PPh Final dan batas bebas pajak tetap berdasarkan akumulasi tahunan pada Bagian B, bukan dihitung ulang per bulan.</div>
        </div>";

        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont'          => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => false,
                'dpi'                  => 96,
                'margin_top'           => 0,
                'margin_bottom'        => 0,
                'margin_left'          => 0,
                'margin_right'         => 0,
            ]);

        return $pdf->download("spt-tahunan-pph-final-{$year}.pdf");
    }

    /**
     * Export Excel — mengikuti struktur resmi Formulir F.1.1.32.04, dengan
     * urutan baris objek pajak sama seperti formulir asli (1 sheet INDUK +
     * 1 sheet lampiran rincian omzet bulanan, meniru pola 2 sheet pada
     * SPT_MASA_PPh_FINAL template: "INDUK" dan lampiran rincian).
     *
     * Hanya untuk WP Orang Pribadi.
     */
    private function exportTaxAsExcel(int $year, string $wpType): \Symfony\Component\HttpFoundation\Response
    {
        $printDate = now()->format('d F Y');

        $omzetTahunan = $this->calcOmzetTahunan($year);
        $hitung       = $this->calcPphFinal($omzetTahunan, 'op');
        $tpct         = min(round(($omzetTahunan / self::TAX_THRESHOLD_OP) * 100, 1), 100);
        $remaining    = max(0, self::TAX_THRESHOLD_OP - $omzetTahunan);
        $taxDeadline  = Carbon::create($year + 1, 3, 31)->format('d F Y');

        $wpName    = config('tax.wp_name',    env('TAX_WP_NAME',    'URBAN KOST'));
        $wpNpwp    = config('tax.wp_npwp',    env('TAX_WP_NPWP',    '00.000.000.0-000.000'));
        $wpAddress = config('tax.wp_address', env('TAX_WP_ADDRESS', 'Yogyakarta'));

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('Urban Kost')
            ->setTitle("SPT Tahunan PPh Final {$year}")
            ->setSubject('PPh Final Pasal 4 Ayat (2) — PP No. 55 Tahun 2022')
            ->setCompany($wpName);

        $FMT_IDR = '#,##0';
        $WHITE   = 'FFFFFFFF';
        $LGRAY   = 'FFF5F5F5';
        $GRAY    = 'FFE0E0E0';

        $borderThin = ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']];
        $borderMed  = ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '000000']];
        $allThin    = ['allBorders' => $borderThin];
        $allMed     = ['allBorders' => $borderMed];

        // ════════════════════════════════════════════════════════
        // SHEET 1 : INDUK — mengikuti struktur formulir resmi
        // ════════════════════════════════════════════════════════
        $sh = $spreadsheet->getActiveSheet()->setTitle('INDUK');
        $sh->getPageSetup()->setFitToWidth(1)->setOrientation('landscape');
        foreach (['A' => 4, 'B' => 38, 'C' => 12, 'D' => 18, 'E' => 8, 'F' => 20] as $col => $w) {
            $sh->getColumnDimension($col)->setWidth($w);
        }

        $row = 1;
        $sh->mergeCells("A{$row}:F{$row}");
        $sh->setCellValue("A{$row}", 'DEPARTEMEN KEUANGAN R.I. — DIREKTORAT JENDERAL PAJAK');
        $sh->getStyle("A{$row}:F{$row}")->applyFromArray(['font' => ['bold' => true, 'size' => 10], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]]);
        $sh->getRowDimension($row)->setRowHeight(18);
        $row++;

        $sh->mergeCells("A{$row}:F{$row}");
        $sh->setCellValue("A{$row}", 'SURAT PEMBERITAHUAN (SPT) TAHUNAN PAJAK PENGHASILAN FINAL PASAL 4 AYAT (2)');
        $sh->getStyle("A{$row}:F{$row}")->applyFromArray(['font' => ['bold' => true, 'size' => 11], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]]);
        $sh->getRowDimension($row)->setRowHeight(20);
        $row++;

        $sh->mergeCells("A{$row}:F{$row}");
        $sh->setCellValue("A{$row}", "Mengacu Formulir F.1.1.32.04 — disesuaikan basis tahunan sesuai PP No. 55 Tahun 2022  |  Tahun Pajak: {$year}  |  Dicetak: {$printDate}");
        $sh->getStyle("A{$row}:F{$row}")->applyFromArray(['font' => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '555555']], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]]);
        $sh->getRowDimension($row)->setRowHeight(14);
        $row += 2;

        // BAGIAN A
        $sh->mergeCells("A{$row}:F{$row}");
        $sh->setCellValue("A{$row}", 'BAGIAN A. IDENTITAS WAJIB PAJAK');
        $sh->getStyle("A{$row}:F{$row}")->applyFromArray(['font' => ['bold' => true, 'size' => 9], 'borders' => $allThin]);
        $sh->getRowDimension($row)->setRowHeight(16);
        $row++;

        foreach ([['1.', 'NPWP', $wpNpwp], ['2.', 'Nama', $wpName], ['3.', 'Alamat', $wpAddress]] as [$no, $lbl, $val]) {
            $sh->setCellValue("A{$row}", $no);
            $sh->setCellValue("B{$row}", $lbl);
            $sh->setCellValue("C{$row}", ':');
            $sh->mergeCells("D{$row}:F{$row}");
            $sh->setCellValue("D{$row}", $val);
            $sh->getStyle("A{$row}:F{$row}")->applyFromArray(['font' => ['size' => 9], 'borders' => $allThin]);
            $sh->getStyle("D{$row}")->getFont()->setBold(true);
            $sh->getRowDimension($row)->setRowHeight(15);
            $row++;
        }
        $row++;

        // BAGIAN B — Objek Pajak (11 baris resmi, mengikuti urutan formulir asli)
        $sh->mergeCells("A{$row}:F{$row}");
        $sh->setCellValue("A{$row}", 'BAGIAN B. OBJEK PAJAK');
        $sh->getStyle("A{$row}:F{$row}")->applyFromArray(['font' => ['bold' => true, 'size' => 9], 'borders' => $allThin]);
        $sh->getRowDimension($row)->setRowHeight(16);
        $row++;

        $hdrRow = $row;
        $headers = ['No.', 'Uraian', 'KAP/KJS', 'Nilai Objek Pajak (Rp)', 'Tarif (%)', 'PPh yang Dipotong/Disetor Sendiri (Rp)'];
        foreach ($headers as $i => $h) $sh->setCellValueByColumnAndRow($i + 1, $row, $h);
        $sh->getStyle("A{$row}:F{$row}")->applyFromArray(['font' => ['bold' => true, 'size' => 8, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '333333']], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true], 'borders' => $allThin]);
        $sh->getRowDimension($row)->setRowHeight(24);
        $row++;

        // Baris-baris resmi 1 s.d. 10 — dikosongkan (tidak relevan untuk usaha kos)
        $objekRows = [
            ['1', 'Bunga Deposito/Tabungan, Diskonto SBI dan Jasa Giro', '411128/404'],
            ['2', 'Transaksi Penjualan Saham', '411128/406-407'],
            ['3', 'Bunga/Diskonto Obligasi dan Surat Berharga Negara', '411128/401'],
            ['4', 'Hadiah Undian', '411128/405'],
            ['5', 'Persewaan Tanah dan/atau Bangunan', '411128/403'],
            ['6', 'Jasa Konstruksi', '411128/409'],
            ['7', 'Pengalihan Hak atas Tanah/Bangunan', '411128/402'],
            ['8', 'Bunga Simpanan Koperasi kepada Anggota WP OP', '411128/417'],
            ['9', 'Transaksi Derivatif Kontrak Berjangka di Bursa', '411128/418'],
            ['10', 'Dividen WP Orang Pribadi Dalam Negeri', '411128/419'],
        ];
        foreach ($objekRows as $idx => [$no, $uraian, $kap]) {
            $sh->setCellValue("A{$row}", $no);
            $sh->setCellValue("B{$row}", $uraian);
            $sh->setCellValue("C{$row}", $kap);
            $sh->setCellValue("D{$row}", '—');
            $sh->setCellValue("E{$row}", '—');
            $sh->setCellValue("F{$row}", '—');
            $bg = $idx % 2 === 0 ? $LGRAY : $WHITE;
            $sh->getStyle("A{$row}:F{$row}")->applyFromArray(['font' => ['size' => 8, 'color' => ['rgb' => '888888']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bg]], 'borders' => $allThin]);
            foreach (['A', 'D', 'E', 'F'] as $c) $sh->getStyle("{$c}{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sh->getRowDimension($row)->setRowHeight(14);
            $row++;
        }

        // Baris 11 — Penghasilan Tertentu Lainnya (DIISI dengan data nyata)
        $sh->setCellValue("A{$row}", '11');
        $sh->setCellValue("B{$row}", 'Penghasilan Tertentu Lainnya — Usaha Jasa Penyewaan Kamar Kos (UMKM, PP No. 55 Tahun 2022)');
        $sh->setCellValue("C{$row}", '411128/420');
        $sh->setCellValue("D{$row}", (float)$hitung['dasar_kena_pajak']);
        $sh->setCellValue("E{$row}", 0.5);
        $sh->setCellValue("F{$row}", (float)$hitung['pph']);
        $sh->getStyle("D{$row}")->getNumberFormat()->setFormatCode($FMT_IDR);
        $sh->getStyle("F{$row}")->getNumberFormat()->setFormatCode($FMT_IDR);
        $sh->getStyle("A{$row}:F{$row}")->applyFromArray(['font' => ['bold' => true, 'size' => 8.5], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFBF0']], 'borders' => $allMed]);
        foreach (['A', 'C', 'E'] as $c) $sh->getStyle("{$c}{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sh->getStyle("D{$row}:F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sh->getRowDimension($row)->setRowHeight(28);
        $sh->getStyle("B{$row}")->getAlignment()->setWrapText(true);
        $row++;

        // Baris JUMLAH
        $sh->mergeCells("A{$row}:C{$row}");
        $sh->setCellValue("A{$row}", 'JUMLAH');
        $sh->setCellValue("D{$row}", (float)$hitung['dasar_kena_pajak']);
        $sh->setCellValue("E{$row}", 0.5);
        $sh->setCellValue("F{$row}", (float)$hitung['pph']);
        $sh->getStyle("D{$row}")->getNumberFormat()->setFormatCode($FMT_IDR);
        $sh->getStyle("F{$row}")->getNumberFormat()->setFormatCode($FMT_IDR);
        $sh->getStyle("A{$row}:F{$row}")->applyFromArray(['font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1A1A1A']], 'borders' => $allMed, 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]]);
        $sh->getStyle("D{$row}:F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sh->getRowDimension($row)->setRowHeight(18);
        $row++;

        // Terbilang
        $taxInt    = (int)$hitung['pph'];
        $terbilang = $taxInt > 0 ? $this->terbilangRupiah($taxInt) . ' rupiah' : 'nihil';
        $sh->mergeCells("A{$row}:F{$row}");
        $sh->setCellValue("A{$row}", 'Terbilang: ' . ucfirst($terbilang));
        $sh->getStyle("A{$row}:F{$row}")->applyFromArray(['font' => ['italic' => true, 'size' => 8.5], 'borders' => $allThin]);
        $sh->getRowDimension($row)->setRowHeight(15);
        $row += 2;

        // Status threshold
        $sh->mergeCells("A{$row}:F{$row}");
        $statusTxt = $hitung['bebas']
            ? "STATUS: BEBAS PPh FINAL — omzet tahun {$year} (Rp " . number_format($omzetTahunan, 0, ',', '.') . ") masih di bawah/sama dengan batas Rp 500.000.000 ({$tpct}% dari batas)."
            : "STATUS: PPh FINAL TERUTANG — omzet tahun {$year} (Rp " . number_format($omzetTahunan, 0, ',', '.') . ") melebihi batas Rp 500.000.000 ({$tpct}% dari batas). Sisa batas: Rp " . number_format($remaining, 0, ',', '.') . ".";
        $sh->setCellValue("A{$row}", $statusTxt);
        $statusBg = $hitung['bebas'] ? 'FFF0FBF4' : 'FFFFFBF0';
        $statusFg = $hitung['bebas'] ? '1A6B2F' : '7A5500';
        $sh->getStyle("A{$row}:F{$row}")->applyFromArray(['font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => $statusFg]], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $statusBg]], 'borders' => $allMed]);
        $sh->getRowDimension($row)->setRowHeight(26);
        $row += 2;

        // BAGIAN C — Lampiran
        $sh->mergeCells("A{$row}:F{$row}");
        $sh->setCellValue("A{$row}", 'BAGIAN C. LAMPIRAN');
        $sh->getStyle("A{$row}:F{$row}")->applyFromArray(['font' => ['bold' => true, 'size' => 9], 'borders' => $allThin]);
        $sh->getRowDimension($row)->setRowHeight(16);
        $row++;

        foreach ([
            ['☑', 'Rincian omzet bulanan tahun ' . $year . ' (lihat sheet "Rincian Omzet Bulanan")'],
            ['☐', 'Surat Setoran Pajak (SSP) / Bukti Penerimaan Negara (BPN)'],
            ['☐', 'Surat Kuasa Khusus (bila SPT ditandatangani oleh kuasa)'],
        ] as $lamp) {
            $sh->setCellValue("A{$row}", $lamp[0]);
            $sh->mergeCells("B{$row}:F{$row}");
            $sh->setCellValue("B{$row}", $lamp[1]);
            $sh->getStyle("A{$row}:F{$row}")->applyFromArray(['font' => ['size' => 9], 'borders' => $allThin]);
            $sh->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sh->getRowDimension($row)->setRowHeight(15);
            $row++;
        }
        $row++;

        // BAGIAN D — Pernyataan & info pelaporan
        $sh->mergeCells("A{$row}:F{$row}");
        $sh->setCellValue("A{$row}", 'BAGIAN D. INFORMASI PELAPORAN DAN PENYETORAN');
        $sh->getStyle("A{$row}:F{$row}")->applyFromArray(['font' => ['bold' => true, 'size' => 9], 'borders' => $allThin]);
        $sh->getRowDimension($row)->setRowHeight(16);
        $row++;

        foreach ([
            ['Jenis Pelaporan', "SPT Tahunan Pajak Penghasilan (tahun pajak {$year})"],
            ['Kode MAP / KJS', '411128 / 420 — PPh Final UMKM Pasal 4 ayat (2)'],
            ['Dasar Hukum', 'PP No. 55 Tahun 2022'],
            ['Batas Pelaporan SPT Tahunan', $taxDeadline],
        ] as $idx => [$lbl, $val]) {
            $sh->setCellValue("A{$row}", $lbl);
            $sh->mergeCells("B{$row}:C{$row}");
            $sh->setCellValue("B{$row}", ': ' . $val);
            $bg = $idx % 2 === 0 ? $LGRAY : $WHITE;
            $sh->mergeCells("D{$row}:F{$row}");
            $sh->getStyle("A{$row}:F{$row}")->applyFromArray(['font' => ['size' => 9], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bg]], 'borders' => $allThin]);
            $sh->getStyle("A{$row}")->getFont()->setBold(true);
            $sh->getRowDimension($row)->setRowHeight(15);
            $row++;
        }
        $row += 2;

        // TTD
        $sh->mergeCells("A{$row}:C{$row}");
        $sh->setCellValue("A{$row}", 'PEMOTONG PAJAK / PIMPINAN');
        $sh->mergeCells("D{$row}:F{$row}");
        $sh->setCellValue("D{$row}", 'KUASA WAJIB PAJAK');
        $sh->getStyle("A{$row}:F{$row}")->applyFromArray(['font' => ['bold' => true, 'size' => 9], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $GRAY]], 'borders' => $allThin, 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]]);
        $sh->getRowDimension($row)->setRowHeight(14);
        $row++;

        $sh->mergeCells("A{$row}:C{$row}");
        $sh->setCellValue("A{$row}", "Nama: {$wpName}\nNPWP: {$wpNpwp}\nTanggal: ___ / ___ / {$year}\n\n[Tanda Tangan & Cap]");
        $sh->mergeCells("D{$row}:F{$row}");
        $sh->setCellValue("D{$row}", "Nama: ____________________\nNPWP: ____________________\nTanggal: ___ / ___ / {$year}\n\n[Tanda Tangan]");
        $sh->getStyle("A{$row}:F{$row}")->applyFromArray(['font' => ['size' => 9], 'borders' => $allThin, 'alignment' => ['wrapText' => true, 'vertical' => Alignment::VERTICAL_TOP]]);
        $sh->getRowDimension($row)->setRowHeight(60);

        // ════════════════════════════════════════════════════════
        // SHEET 2 : RINCIAN OMZET BULANAN — lampiran pendukung
        // ════════════════════════════════════════════════════════
        $sh2 = $spreadsheet->createSheet()->setTitle('Rincian Omzet Bulanan');
        $sh2->getPageSetup()->setOrientation('landscape');
        foreach (['A' => 20, 'B' => 20, 'C' => 16, 'D' => 16] as $col => $w) {
            $sh2->getColumnDimension($col)->setWidth($w);
        }

        $r2 = 1;
        $sh2->mergeCells("A{$r2}:D{$r2}");
        $sh2->setCellValue("A{$r2}", "LAMPIRAN — RINCIAN OMZET PER BULAN TAHUN {$year}");
        $sh2->getStyle("A{$r2}:D{$r2}")->applyFromArray(['font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1A1A1A']], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]]);
        $sh2->getRowDimension($r2)->setRowHeight(22);
        $r2++;

        $sh2->mergeCells("A{$r2}:D{$r2}");
        $sh2->setCellValue("A{$r2}", "Lampiran pendukung Bagian B SPT Tahunan — bersifat informatif, bukan basis perhitungan terpisah  |  Dicetak: {$printDate}");
        $sh2->getStyle("A{$r2}:D{$r2}")->applyFromArray(['font' => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '555555']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]]);
        $sh2->getRowDimension($r2)->setRowHeight(14);
        $r2 += 2;

        $hdrs2 = ['Bulan', 'Omzet Lunas (Rp)', 'Jumlah Invoice Lunas', 'Kumulatif (Rp)'];
        foreach ($hdrs2 as $i => $h) $sh2->setCellValueByColumnAndRow($i + 1, $r2, $h);
        $sh2->getStyle("A{$r2}:D{$r2}")->applyFromArray(['font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2B2B2B']], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER], 'borders' => $allThin]);
        $sh2->getRowDimension($r2)->setRowHeight(18);
        $r2++;

        $dataStart = $r2;
        $kumulatif = 0;
        for ($m = 1; $m <= 12; $m++) {
            $rev = (float)Invoice::where('status', 'paid')->whereYear('period_start', $year)->whereMonth('period_start', $m)->sum('total_amount');
            $cnt = Invoice::where('status', 'paid')->whereYear('period_start', $year)->whereMonth('period_start', $m)->count();
            $kumulatif += $rev;

            $sh2->setCellValue("A{$r2}", Carbon::create($year, $m, 1)->translatedFormat('F Y'));
            $sh2->setCellValue("B{$r2}", $rev);
            $sh2->setCellValue("C{$r2}", $cnt);
            $sh2->setCellValue("D{$r2}", $kumulatif);
            $sh2->getStyle("B{$r2}")->getNumberFormat()->setFormatCode($FMT_IDR);
            $sh2->getStyle("D{$r2}")->getNumberFormat()->setFormatCode($FMT_IDR);
            $bg = $m % 2 === 0 ? $LGRAY : $WHITE;
            $sh2->getStyle("A{$r2}:D{$r2}")->applyFromArray(['font' => ['size' => 9], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bg]], 'borders' => $allThin]);
            $sh2->getStyle("B{$r2}:D{$r2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sh2->getStyle("C{$r2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sh2->getRowDimension($r2)->setRowHeight(15);
            $r2++;
        }
        $sh2->setCellValue("A{$r2}", 'TOTAL TAHUN ' . $year);
        $sh2->setCellValue("B{$r2}", "=SUM(B{$dataStart}:B" . ($r2 - 1) . ')');
        $sh2->setCellValue("C{$r2}", "=SUM(C{$dataStart}:C" . ($r2 - 1) . ')');
        $sh2->setCellValue("D{$r2}", $kumulatif);
        $sh2->getStyle("B{$r2}")->getNumberFormat()->setFormatCode($FMT_IDR);
        $sh2->getStyle("D{$r2}")->getNumberFormat()->setFormatCode($FMT_IDR);
        $sh2->getStyle("A{$r2}:D{$r2}")->applyFromArray(['font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1A1A1A']], 'borders' => $allMed]);
        $sh2->getStyle("B{$r2}:D{$r2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sh2->getRowDimension($r2)->setRowHeight(18);

        $spreadsheet->setActiveSheetIndex(0);
        $filename = "spt-tahunan-pph-final-{$year}.xlsx";

        return response()->streamDownload(function () use ($spreadsheet) {
            (new XlsxWriter($spreadsheet))->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
            'Pragma'              => 'no-cache',
            'Expires'             => '0',
        ]);
    }

    /**
     * Helper: terbilang (angka -> kata Indonesia), dipakai untuk baris "Terbilang".
     */
    private function terbilangRupiah(int $n): string
    {
        if ($n === 0) return 'nol';
        $satuan = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
        if ($n < 12)  return $satuan[$n];
        if ($n < 20)  return $satuan[$n - 10] . ' belas';
        if ($n < 100) return $satuan[(int)($n / 10)] . ' puluh' . ($n % 10 ? ' ' . $this->terbilangRupiah($n % 10) : '');
        if ($n < 200) return 'seratus' . ($n % 100 ? ' ' . $this->terbilangRupiah($n % 100) : '');
        if ($n < 1000) return $satuan[(int)($n / 100)] . ' ratus' . ($n % 100 ? ' ' . $this->terbilangRupiah($n % 100) : '');
        if ($n < 2000) return 'seribu' . ($n % 1000 ? ' ' . $this->terbilangRupiah($n % 1000) : '');
        if ($n < 1_000_000) return $this->terbilangRupiah((int)($n / 1000)) . ' ribu' . ($n % 1000 ? ' ' . $this->terbilangRupiah($n % 1000) : '');
        if ($n < 1_000_000_000) return $this->terbilangRupiah((int)($n / 1_000_000)) . ' juta' . ($n % 1_000_000 ? ' ' . $this->terbilangRupiah($n % 1_000_000) : '');
        return $this->terbilangRupiah((int)($n / 1_000_000_000)) . ' miliar' . ($n % 1_000_000_000 ? ' ' . $this->terbilangRupiah($n % 1_000_000_000) : '');
    }

    private function invoiceTypeLabel(string $type): string
    {
        return match (true) {
            in_array($type, ['monthly', 'monthly_first', 'waiting_list_dp', 'waiting_list_remaining']) => 'Sewa',
            $type === 'renewal' => 'Perpanjangan',
            default             => ucfirst($type),
        };
    }

    private function calcLatePaymentRate(int $year, int $month): float
    {
        $total = Invoice::whereYear('period_start', $year)->whereMonth('period_start', $month)->count();
        if ($total === 0) return 0.0;

        $late  = Invoice::where('status', 'paid')->whereYear('period_start', $year)->whereMonth('period_start', $month)->whereRaw('paid_at > due_date')->count();
        $late += Invoice::where('status', 'overdue')->whereYear('period_start', $year)->whereMonth('period_start', $month)->count();

        return round(($late / $total) * 100, 1);
    }

    private function calcAvgMttr(int $year): float
    {
        $resolved = Ticket::whereYear('created_at', $year)->whereNotNull('resolved_at')->get();
        if ($resolved->isEmpty()) return 0.0;

        $totalHours = $resolved->sum(fn($t) => $t->created_at->diffInMinutes($t->resolved_at) / 60);
        return round($totalHours / $resolved->count(), 1);
    }
}