<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use App\Models\WaitingList;
use App\Models\Invoice;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ServiceCatalog;

class BookingController extends Controller
{
    private NotificationService $notif;

    private function getAdditionalServices(): array
    {
        return ServiceCatalog::toBookingArray();
    }

    public function __construct()
    {
        $this->notif = new NotificationService();
    }

    public function create($room_id)
    {
        $room = Room::with([
            'bookings' => fn($q) => $q->whereIn('status', ['approved', 'active'])
                                    ->with('user')
                                    ->latest()
        ])->findOrFail($room_id);

        if ($room->status === 'maintenance') {
            return redirect()->route('home')
                ->with('error', 'Kamar sedang dalam perbaikan dan tidak dapat dibooking.');
        }

        if (!auth()->check()) {
            session(['intended_booking_room' => $room_id]);
            return redirect()->route('login')
                ->with('info', 'Silakan login terlebih dahulu untuk melakukan booking.');
        }

        $user = auth()->user();

        $userActiveBooking = Booking::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'active'])
            ->with('room')
            ->first();

        if ($userActiveBooking) {
            return redirect()->route('home')
                ->with('warning',
                    'Anda sudah menghuni kamar <strong>' .
                    ($userActiveBooking->room?->room_number ?? '-') .
                    '</strong>. Selesaikan masa sewa terlebih dahulu sebelum booking kamar baru.'
                );
        }

        $existingActiveBooking = Booking::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'under_review', 'waiting_list'])
            ->with('room')
            ->first();

        if ($existingActiveBooking) {
            return redirect()->route('home')
                ->with('warning',
                    'Anda masih memiliki permohonan booking yang sedang diproses ' .
                    '(kode: <strong>' . $existingActiveBooking->booking_code . '</strong>, ' .
                    'kamar: <strong>' . ($existingActiveBooking->room?->room_number ?? '-') . '</strong>). ' .
                    'Tunggu hingga selesai atau batalkan terlebih dahulu.'
                );
        }

        $isAdvance         = in_array($room->status, ['occupied', 'reserved']);
        $currentUser       = null;
        $activeWaitingList = null;
        $waitingListFull   = false;
        $tenantEndDate     = null;
        $minCheckInDate    = null;

        if ($isAdvance) {
            $activeTenantBooking = $room->bookings->first();

            if ($activeTenantBooking?->check_in_date && $activeTenantBooking?->duration_months) {
                $tenantEndDate = Carbon::parse($activeTenantBooking->check_in_date)
                    ->addMonths($activeTenantBooking->duration_months);

                $minCheckInDate = $tenantEndDate->isPast()
                    ? Carbon::today()
                    : $tenantEndDate->copy()->addDays(2);
            } else {
                $minCheckInDate = Carbon::today();
            }

            $currentUser = User::whereHas('role', fn($q) => $q->where('name', 'tenant'))
                ->where('is_active', true)
                ->whereHas('bookings', fn($q) => $q
                    ->where('room_id', $room->id)
                    ->whereIn('status', ['approved', 'active'])
                )
                ->with(['bookings' => fn($q) => $q
                    ->where('room_id', $room->id)
                    ->whereIn('status', ['approved', 'active'])
                    ->latest()
                ])
                ->first();

            $activeWaitingList = WaitingList::getActiveForRoom($room->id);
            $waitingListFull   = $activeWaitingList !== null;
        }

        $userAlreadyInWaitingList = WaitingList::where('user_id', $user->id)
            ->where('room_id', $room->id)
            ->whereIn('status', ['waiting', 'notified'])
            ->exists();

        $additionalServices = $this->getAdditionalServices();

        return view('home.booking-form', compact(
            'room',
            'isAdvance',
            'currentUser',
            'activeWaitingList',
            'waitingListFull',
            'userAlreadyInWaitingList',
            'additionalServices',
            'tenantEndDate',
            'minCheckInDate'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $userActiveBooking = Booking::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'active'])
            ->first();

        if ($userActiveBooking) {
            return back()->with('error',
                'Anda sudah memiliki kamar aktif. Tidak dapat melakukan booking baru.'
            );
        }

        $room = Room::with([
            'bookings' => fn($q) => $q->whereIn('status', ['approved', 'active'])->latest()
        ])->findOrFail($request->room_id ?? 0);

        $isAdvance        = in_array($room->status, ['occupied', 'reserved']);
        $minCheckInString = 'today';

        if ($isAdvance) {
            $activeTenantBooking = $room->bookings->first();

            if ($activeTenantBooking?->check_in_date && $activeTenantBooking?->duration_months) {
                $tenantEnd = Carbon::parse($activeTenantBooking->check_in_date)
                    ->addMonths($activeTenantBooking->duration_months);

                $minCheckInString = $tenantEnd->isPast()
                    ? Carbon::today()->format('Y-m-d')
                    : $tenantEnd->copy()->addDays(2)->format('Y-m-d');
            } else {
                $minCheckInString = Carbon::today()->format('Y-m-d');
            }
        }

        $validServiceKeys = ServiceCatalog::validKeys();

        $request->validate([
            'room_id'           => 'required|exists:rooms,id',
            'full_name'         => 'required|string|max:100',
            'phone'             => 'required|string|max:25',
            'identity_number'   => 'nullable|string|max:30',
            'emergency_contact' => 'required|string|max:50',
            'check_in_date'     => [
                'required', 'date', 'after_or_equal:' . $minCheckInString,
            ],
            'duration_months'   => 'required|integer|min:1|max:24',
            'message'           => 'nullable|string|max:500',
            'services'          => 'nullable|array',
            'services.*'        => 'string|in:' . implode(',', $validServiceKeys),
            'agree_terms'       => 'accepted',
            'payment_method'    => 'required|in:lump_sum,monthly',
        ], [
            'full_name.required'           => 'Nama lengkap wajib diisi.',
            'phone.required'               => 'Nomor HP wajib diisi.',
            'emergency_contact.required'   => 'Nomor darurat wajib diisi.',
            'check_in_date.required'       => 'Tanggal masuk wajib diisi.',
            'check_in_date.after_or_equal' => $isAdvance
                ? 'Tanggal masuk tidak boleh sebelum ' . $minCheckInString . ' (2 hari setelah penghuni aktif selesai).'
                : 'Tanggal masuk tidak boleh sebelum hari ini.',
            'duration_months.required'     => 'Durasi sewa wajib diisi.',
            'agree_terms.accepted'         => 'Anda wajib menyetujui Syarat & Ketentuan sebelum melanjutkan.',
            'payment_method.required'      => 'Pilih metode pembayaran.',
            'payment_method.in'            => 'Metode pembayaran tidak valid.',
        ]);

        if ($room->status === 'maintenance') {
            return back()->with('error', 'Kamar sedang dalam perbaikan dan tidak dapat dibooking.');
        }

        $existingBooking = Booking::where('user_id', $user->id)
            ->where('room_id', $request->room_id)
            ->whereIn('status', ['pending', 'under_review', 'waiting_list'])
            ->first();

        if ($existingBooking) {
            return back()->with('warning',
                'Anda sudah memiliki permohonan booking untuk kamar ini ' .
                '(kode: <strong>' . $existingBooking->booking_code . '</strong>, status: ' . $existingBooking->status . ').'
            );
        }

        $catalogServices       = $this->getAdditionalServices();
        $selectedServices      = $request->services ?? [];
        $serviceAmountPerMonth = 0;
        $serviceDetails        = [];

        foreach ($selectedServices as $key) {
            if (isset($catalogServices[$key])) {
                $svc                   = $catalogServices[$key];
                $serviceAmountPerMonth += $svc['price'];
                $serviceDetails[$key]  = $svc;
            }
        }

        $bookingType      = $isAdvance ? 'advance' : 'regular';
        $advanceForUserId = null;

        if ($isAdvance) {
            if (WaitingList::hasActiveForRoom($room->id)) {
                return back()->with('error',
                    'Kamar ini sudah memiliki <strong>Waiting List aktif</strong>. ' .
                    'Silakan cek kamar lain yang tersedia.'
                );
            }

            if (WaitingList::where('user_id', $user->id)
                ->where('room_id', $room->id)
                ->whereIn('status', ['waiting', 'notified'])
                ->exists()) {
                return back()->with('warning', 'Anda sudah berada di waiting list kamar ini.');
            }

            $currentTenant = User::whereHas('role', fn($q) => $q->where('name', 'tenant'))
                ->where('is_active', true)
                ->whereHas('bookings', fn($q) => $q
                    ->where('room_id', $room->id)
                    ->whereIn('status', ['approved', 'active'])
                )
                ->first();

            $advanceForUserId = $currentTenant?->id;
        }

        $booking = Booking::create([
            'user_id'                 => $user->id,
            'room_id'                 => $request->room_id,
            'status'                  => 'pending',
            'booking_type'            => $bookingType,
            'payment_method'          => $request->payment_method,   
            'advance_for_user_id'     => $advanceForUserId,
            'renewal_status'          => $isAdvance ? 'pending_inquiry' : null,
            'renewal_inquiry_sent_at' => $isAdvance ? now() : null,
            'full_name'               => $request->full_name,
            'phone'                   => $request->phone,
            'identity_number'         => $request->identity_number,
            'emergency_contact'       => $request->emergency_contact,
            'check_in_date'           => $request->check_in_date,
            'duration_months'         => $request->duration_months,
            'message'                 => $request->message,
            'additional_services'     => !empty($serviceDetails) ? $serviceDetails : null,
            'service_amount'          => $serviceAmountPerMonth,
        ]);

        $this->notif->notifyNewBooking($booking);

        $duration   = (int) $request->duration_months;
        $totalSewa  = ($room->price ?? 0) * $duration;
        $totalSvc   = $serviceAmountPerMonth * $duration;
        $grandTotal = $totalSewa + $totalSvc;

        $paymentMethodLabel = $request->payment_method === 'monthly' ? 'bulanan' : 'lunas';
        $estimasiTagihan    = $request->payment_method === 'monthly'
            ? 'Rp ' . number_format(($room->price ?? 0) + $serviceAmountPerMonth, 0, ',', '.') . '/bulan'
            : 'Rp ' . number_format($grandTotal, 0, ',', '.');

        $redirectRoute = $user->isProspective()
            ? 'prospective.bookings'
            : ($user->isTenant() ? 'tenant.bookings' : 'home');

        $msg = ' Permohonan booking berhasil dikirim! Kode: <strong>' . $booking->booking_code . '</strong>. ' .
            'Metode pembayaran: <strong>' . ucfirst($paymentMethodLabel) . '</strong>. ' .
            'Estimasi tagihan: <strong>' . $estimasiTagihan . '</strong> ' .
            'untuk ' . $duration . ' bulan. ' .
            'Permohonan akan diverifikasi dalam <strong>1×24 jam</strong>. ' .
            'Informasi selanjutnya akan dikirimkan ke nomor WhatsApp terdaftar Anda.';

        if ($isAdvance) {
            $dpLabel = $request->payment_method === 'monthly'
                ? '50% dari biaya bulan pertama (Rp ' . number_format((int) round((($room->price ?? 0) + $serviceAmountPerMonth) * 0.5), 0, ',', '.') . ')'
                : '50% dari total sewa (Rp ' . number_format((int) round($grandTotal * 0.5), 0, ',', '.') . ')';

            $msg .= ' Karena kamar masih ditempati, jika disetujui Anda akan masuk <strong>Waiting List</strong> ' .
                    'dengan DP sebesar <strong>' . $dpLabel . '</strong>.';
        }

        return redirect()->route($redirectRoute)->with('success', $msg);
    }

    public function myBookings(Request $request)
    {
        $statusFilter = $request->query('status');

        $query = Booking::where('user_id', auth()->id())
            ->with([
                'room',
                'waitingList' => fn($q) => $q->with('room'),
            ])
            ->orderBy('created_at', 'desc');

        $allowedStatuses = ['pending', 'approved', 'rejected', 'waiting_list', 'cancelled'];
        if ($statusFilter && in_array($statusFilter, $allowedStatuses)) {
            $query->where('status', $statusFilter);
        }

        $bookings = $query->paginate(10)->withQueryString();

        $wlData = [];

        foreach ($bookings as $b) {
            if ($b->status !== 'waiting_list' || !$b->waitingList) {
                continue;
            }

            $wl       = $b->waitingList;
            $invoices = $this->_getWlInvoicesForBooking($wl);

            foreach ($invoices as $inv) {
                if (!$inv->waiting_list_id) {
                    $inv->updateQuietly(['waiting_list_id' => $wl->id]);
                }
            }

            $fin = $this->_calcWlFinancials($wl, $invoices, $b->payment_method);

            $activeInvoice = null;
            if ($fin['remainingInvoice'] &&
                in_array($fin['remainingInvoice']->status, ['unpaid', 'overdue', 'pending_verification'])) {
                $activeInvoice = $fin['remainingInvoice'];
            } elseif ($fin['dpInvoice'] &&
                in_array($fin['dpInvoice']->status, ['unpaid', 'overdue', 'pending_verification'])) {
                $activeInvoice = $fin['dpInvoice'];
            }

            $wlData[$b->id] = [
                'wlEntry'       => $wl,
                'dpInvoice'     => $fin['dpInvoice'],
                'sisaInvoice'   => $fin['remainingInvoice'],
                'activeInvoice' => $activeInvoice,
                'totalSewa'     => $fin['totalSewa'],
                'dpAmount'      => $fin['dpAmount'],
                'sisaAmount'    => $fin['sisaAmount'],
                'totalPaid'     => $fin['totalPaid'],
                'remaining'     => $fin['remaining'],
                'dpIsPaid'      => $fin['dpIsPaid'],
                'sisaIsPaid'    => $fin['remainingInvoice'] && $fin['remainingInvoice']->status === 'paid',
                'dpPct'         => $fin['dpAmount'] > 0
                                    ? min(100, round(($fin['totalPaid'] / $fin['dpAmount']) * 100))
                                    : 0,
                'roomPrice'     => $fin['roomPrice'],
                'serviceAmount' => $fin['serviceAmount'],
                'duration'      => $fin['duration'],
                'paymentMethod' => $b->payment_method,  
            ];
        }

        $allBookings = Booking::where('user_id', auth()->id())->get();

        $counts = [
            'all'          => $allBookings->count(),
            'pending'      => $allBookings->where('status', 'pending')->count(),
            'approved'     => $allBookings->where('status', 'approved')->count(),
            'waiting_list' => $allBookings->where('status', 'waiting_list')->count(),
            'rejected'     => $allBookings->where('status', 'rejected')->count(),
            'cancelled'    => $allBookings->where('status', 'cancelled')->count(),
        ];

        $hasActiveBooking = $allBookings
            ->whereIn('status', ['approved', 'waiting_list'])
            ->count() > 0;

        $canBook = !$hasActiveBooking;

        return view('home.my-bookings', compact(
            'bookings',
            'wlData',
            'counts',
            'hasActiveBooking',
            'canBook',
            'statusFilter'
        ));
    }

    private function _getWlInvoicesForBooking(WaitingList $wl)
    {
        return Invoice::where(function ($q) use ($wl) {
                $q->where('waiting_list_id', $wl->id);
            })
            ->orWhere(function ($q) use ($wl) {
                $q->where('user_id',  $wl->user_id)
                  ->where('room_id',  $wl->room_id)
                  ->where(function ($q2) {
                      $q2->where('invoice_number', 'like', '%-DP-%')
                         ->orWhere('invoice_number', 'like', '%-SISA-%');
                  });
            })
            ->orderBy('created_at')
            ->get()
            ->unique('id');
    }

    private function _calcWlFinancials(WaitingList $wl, $invoices, string $paymentMethod = 'lump_sum')
    {
        $roomPrice     = (float) ($wl->room?->price ?? 0);
        $serviceAmount = (float) ($wl->booking?->service_amount ?? 0);
        $duration      = (int)   ($wl->booking?->duration_months ?? 1);

        $totalSewa = ($roomPrice + $serviceAmount) * $duration;

        $dpInvoice        = $invoices->first(fn($i) => str_contains($i->invoice_number, '-DP-'));
        $remainingInvoice = $invoices->first(fn($i) => str_contains($i->invoice_number, '-SISA-'));

        if ($dpInvoice) {
            $dpAmount = (float) $dpInvoice->total_amount;
        } elseif ($paymentMethod === 'monthly') {
            $dpAmount = round(($roomPrice + $serviceAmount) * 0.5);
        } else {
            $dpAmount = round($totalSewa * 0.5);
        }

        $sisaAmount = $remainingInvoice
            ? (float) $remainingInvoice->total_amount
            : max(0, ($paymentMethod === 'monthly'
                ? ($roomPrice + $serviceAmount) - $dpAmount   
                : $totalSewa - $dpAmount                       
              ));

        $totalPaid = (float) $invoices->where('status', 'paid')->sum('total_amount');
        $remaining = max(0, $totalSewa - $totalPaid);
        $dpIsPaid  = $dpInvoice && $dpInvoice->status === 'paid';

        return compact(
            'totalSewa', 'roomPrice', 'serviceAmount', 'duration',
            'dpAmount', 'sisaAmount',
            'totalPaid', 'remaining',
            'dpIsPaid', 'dpInvoice', 'remainingInvoice'
        );
    }
}
