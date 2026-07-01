<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Room;
use App\Models\Ticket;
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

class ReportController extends Controller
{
    // =========================================================================
    // INDEX — sama persis dengan sebelumnya, tidak diubah
    // =========================================================================
    public function index(Request $request)
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
            $paid   = Invoice::where('status', 'paid')->whereYear('period_start', $year)->whereMonth('period_start', $m)->sum('total_amount');
            $unpaid = Invoice::whereIn('status', ['unpaid', 'overdue'])->whereYear('period_start', $year)->whereMonth('period_start', $m)->sum('total_amount');
            $monthlyData[] = [
                'month'      => Carbon::create($year, $m, 1)->format('M'),
                'revenue'    => $paid,
                'unpaid'     => $unpaid,
                'invoices'   => Invoice::whereYear('period_start', $year)->whereMonth('period_start', $m)->count(),
                'paid_count' => Invoice::where('status', 'paid')->whereYear('period_start', $year)->whereMonth('period_start', $m)->count(),
            ];
        }

        $rawTypes = Invoice::where('status', 'paid')->whereYear('period_start', $year)
            ->select('invoice_type', DB::raw('SUM(total_amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('invoice_type')->get();

        $revenueByType = collect([
            (object)['type' => 'sewa',         'label' => 'Sewa',         'total' => 0, 'count' => 0],
            (object)['type' => 'perpanjangan', 'label' => 'Perpanjangan', 'total' => 0, 'count' => 0],
        ]);
        foreach ($rawTypes as $rt) {
            $key = $rt->invoice_type === 'renewal' ? 'perpanjangan' : 'sewa';
            $revenueByType->firstWhere('type', $key)->total += $rt->total;
            $revenueByType->firstWhere('type', $key)->count += $rt->count;
        }
        $revenueByType = $revenueByType->filter(fn($g) => $g->total > 0)->values();

        $topRooms = Invoice::where('status', 'paid')
            ->whereYear('period_start', $year)
            ->select('room_id', DB::raw('SUM(total_amount) as total_revenue'), DB::raw('COUNT(*) as invoice_count'))
            ->groupBy('room_id')
            ->with('room:id,room_number,room_name,price')
            ->orderByDesc('total_revenue')
            ->limit(5)->get();

        $cashFlowQuery = Invoice::with(['user:id,name,email', 'room:id,room_number,room_name', 'booking:id,booking_code'])
            ->whereYear('created_at', $year);
        if ($request->filled('cf_month') && $request->cf_month !== 'all') $cashFlowQuery->whereMonth('created_at', (int)$request->cf_month);
        if ($request->filled('cf_status') && $request->cf_status !== 'all') $cashFlowQuery->where('status', $request->cf_status);
        if ($request->filled('cf_type')   && $request->cf_type   !== 'all') $cashFlowQuery->where('invoice_type', $request->cf_type);
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

        $rooms  = Room::all();
        $totalR = $rooms->count();

        $bookingQuery = Booking::with(['user:id,name,email,phone', 'room:id,room_number,room_name,price'])->whereYear('created_at', $year);
        if ($request->filled('bk_room')   && $request->bk_room   !== 'all') $bookingQuery->where('room_id', (int)$request->bk_room);
        if ($request->filled('bk_status') && $request->bk_status !== 'all') $bookingQuery->where('status', $request->bk_status);
        if ($request->filled('bk_month')  && $request->bk_month  !== 'all') $bookingQuery->whereMonth('created_at', (int)$request->bk_month);
        $bookingDetail = $bookingQuery->orderByDesc('created_at')->paginate(20, ['*'], 'bk_page')->withQueryString();

        $allRooms = Room::orderBy('room_number')->get();
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

        $latePaymentRate = $this->calcLatePaymentRate($year, $month);

        $latePayments = Invoice::where('status', 'paid')->whereYear('period_start', $year)->whereRaw('paid_at > due_date')
            ->select(DB::raw('MONTH(period_start) as m'), DB::raw('COUNT(*) as count'), DB::raw('AVG(DATEDIFF(paid_at, due_date)) as avg_days_late'), DB::raw('MAX(DATEDIFF(paid_at, due_date)) as max_days_late'))
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

        $chronicallyLateUsers = Invoice::where('status', 'paid')->whereYear('period_start', $year)->whereRaw('paid_at > due_date')
            ->select('user_id', DB::raw('COUNT(*) as late_count'), DB::raw('AVG(DATEDIFF(paid_at, due_date)) as avg_days'), DB::raw('SUM(total_amount) as total_paid'))
            ->groupBy('user_id')->having('late_count', '>=', 2)->with('user:id,name,email,phone')
            ->orderByDesc('late_count')->limit(10)->get();

        $lateInvoiceQuery = Invoice::with(['user:id,name,email', 'room:id,room_number'])->where('status', 'paid')->whereYear('period_start', $year)->whereRaw('paid_at > due_date');
        if ($request->filled('lt_month') && $request->lt_month !== 'all') $lateInvoiceQuery->whereMonth('period_start', (int)$request->lt_month);
        $lateInvoiceDetail = $lateInvoiceQuery->orderByDesc(DB::raw('DATEDIFF(paid_at, due_date)'))->paginate(15, ['*'], 'lt_page')->withQueryString();

        $currentOverdue = Invoice::with(['user:id,name,email', 'room:id,room_number'])->where('status', 'overdue')->orderByDesc('due_date')->get();

        $totalInvoicesYear = Invoice::whereYear('created_at', $year)->count();
        $paidCount         = Invoice::where('status', 'paid')->whereYear('created_at', $year)->count();
        $unpaidCount       = Invoice::whereIn('status', ['unpaid', 'overdue'])->whereYear('created_at', $year)->count();
        $lateCount         = Invoice::where('status', 'paid')->whereYear('created_at', $year)->whereRaw('paid_at > due_date')->count();

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

        $ticketsByPriority = Ticket::selectRaw('priority, COUNT(*) as count')->whereYear('created_at', $year)->groupBy('priority')->get()->keyBy('priority');

        $ticketQuery = Ticket::with(['user:id,name,email', 'assignee:id,name', 'replies'])->whereYear('created_at', $year);
        if ($request->filled('tk_status')   && $request->tk_status   !== 'all') $ticketQuery->where('status', $request->tk_status);
        if ($request->filled('tk_priority') && $request->tk_priority !== 'all') $ticketQuery->where('priority', $request->tk_priority);
        if ($request->filled('tk_category') && $request->tk_category !== 'all') $ticketQuery->where('category', $request->tk_category);
        if ($request->filled('tk_month')    && $request->tk_month    !== 'all') $ticketQuery->whereMonth('created_at', (int)$request->tk_month);
        $ticketDetail = $ticketQuery->orderByDesc('created_at')->paginate(15, ['*'], 'tk_page')->withQueryString();

        $urgentOpenTickets = Ticket::with(['user:id,name,email', 'assignee:id,name'])->where('priority', 'urgent')->whereIn('status', ['open', 'in_progress'])->orderBy('created_at')->get();
        $ticketCategories  = Ticket::whereYear('created_at', $year)->distinct()->pluck('category');

        return view('admin.reports.index', compact(
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

    // =========================================================================
    // EXPORT ROUTER
    // =========================================================================
    public function export(Request $request, string $section)
    {
        $year   = (int)($request->year  ?? now()->year);
        $month  = (int)($request->month ?? now()->month);
        $format = $request->format ?? 'excel';

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
                $filename = "laporan-pendapatan-{$year}.xlsx";
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
                $filename = "laporan-booking-{$year}.xlsx";
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
                $filename = "laporan-keterlambatan-{$year}.xlsx";
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
                $filename = "laporan-tiket-{$year}.xlsx";
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
                $filename = "laporan-pendapatan-{$year}.pdf";
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
                $filename = "laporan-booking-{$year}.pdf";
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
                $filename = "laporan-keterlambatan-{$year}.pdf";
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
                $filename = "laporan-tiket-{$year}.pdf";
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

    private function calcLatePaymentRate(int $year, int $month): float
    {
        $total = Invoice::whereYear('period_start', $year)->whereMonth('period_start', $month)->count();
        if ($total === 0) return 0;
        $late  = Invoice::where('status', 'paid')->whereYear('period_start', $year)->whereMonth('period_start', $month)->whereRaw('paid_at > due_date')->count();
        $late += Invoice::where('status', 'overdue')->whereYear('period_start', $year)->whereMonth('period_start', $month)->count();
        return round(($late / $total) * 100, 1);
    }

    private function calcAvgMttr(int $year): float
    {
        $resolved = Ticket::whereYear('created_at', $year)->whereNotNull('resolved_at')->get();
        if ($resolved->isEmpty()) return 0;
        $totalHours = $resolved->sum(fn($t) => $t->created_at->diffInMinutes($t->resolved_at) / 60);
        return round($totalHours / $resolved->count(), 1);
    }

    private function invoiceTypeLabel(string $type): string
    {
        return match (true) {
            in_array($type, ['monthly', 'monthly_first', 'waiting_list_dp', 'waiting_list_remaining']) => 'Sewa',
            $type === 'renewal' => 'Perpanjangan',
            default             => ucfirst($type),
        };
    }
}