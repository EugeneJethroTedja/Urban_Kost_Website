<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\InAppNotification;
use App\Models\Invoice;
use App\Models\Renewal;
use App\Models\ServiceCatalog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;

class RenewalController extends Controller
{
    private function getServiceCatalog(): array
    {
        return ServiceCatalog::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->keyBy('key')
            ->map(fn($s) => [
                'label' => $s->name,
                'icon'  => $s->icon ?? '',
                'price' => (int) $s->price,
            ])
            ->toArray();
    }

    public function index()
    {
        $user = auth()->user();

        $activeBooking = Booking::with('room')
            ->where('user_id', $user->id)
            ->whereIn('status', ['approved', 'active'])
            ->latest()
            ->first();

        $hasAdvanceBooker   = false;
        $hasWaitingList     = false;
        $renewalBlocked     = false;
        $renewalBlockReason = null;
        $daysUntilEnd       = null;
        $tooLateToRenew     = false;

        if ($activeBooking) {
            if (!empty($activeBooking->end_date)) {
                $activeBooking->computed_end_date = Carbon::parse($activeBooking->end_date);
            } elseif ($activeBooking->check_in_date && $activeBooking->duration_months) {
                $activeBooking->computed_end_date = Carbon::parse($activeBooking->check_in_date)
                    ->addMonths($activeBooking->duration_months);
            } else {
                $activeBooking->computed_end_date = null;
            }

            $hasAdvanceBooker = Booking::where('room_id', $activeBooking->room_id)
                ->where('booking_type', 'advance')
                ->whereIn('status', ['pending', 'under_review', 'approved'])
                ->exists();

            $hasWaitingList = \App\Models\WaitingList::hasActiveForRoom($activeBooking->room_id);

            if ($activeBooking->computed_end_date) {
                $daysUntilEnd   = (int) now()->diffInDays($activeBooking->computed_end_date, false);
                $tooLateToRenew = $daysUntilEnd < 10;
            }

            if ($hasAdvanceBooker) {
                $renewalBlocked     = true;
                $renewalBlockReason = 'advance_booker';
            } elseif ($hasWaitingList) {
                $renewalBlocked     = true;
                $renewalBlockReason = 'waiting_list';
            } elseif ($tooLateToRenew) {
                $renewalBlocked     = true;
                $renewalBlockReason = 'too_late';
            }
        }

        $pendingRenewal = Renewal::where('user_id', $user->id)
            ->whereIn('status', ['pending_admin', 'pending_payment'])
            ->latest()
            ->first();

        $renewals = Renewal::with(['room', 'invoice'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $SERVICES = $this->getServiceCatalog();

        return view('tenant.renewal.index', compact(
            'activeBooking',
            'pendingRenewal',
            'renewals',
            'hasAdvanceBooker',
            'hasWaitingList',
            'renewalBlocked',
            'renewalBlockReason',
            'daysUntilEnd',
            'tooLateToRenew',
            'SERVICES'
        ));
    }

    public function show($id)
    {
        $renewal = Renewal::with(['room', 'invoice.payments', 'booking'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $payments = $renewal->invoice?->payments()->orderBy('created_at', 'desc')->get()
            ?? collect();

        return view('tenant.renewal.show', compact('renewal', 'payments'));
    }

    public function edit($id)
    {
        $renewal = Renewal::with(['room', 'booking'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        if ($renewal->status !== 'pending_admin') {
            return redirect()
                ->route('tenant.renewal.show', $renewal->id)
                ->with('warning', 'Perpanjangan tidak dapat diubah karena sudah diproses atau dibayar.');
        }

        $activeBooking = Booking::with('room')
            ->where('user_id', auth()->id())
            ->whereIn('status', ['approved', 'active'])
            ->latest()
            ->first();

        if (!$activeBooking) {
            return redirect()
                ->route('tenant.renewal.index')
                ->with('error', 'Tidak ditemukan booking aktif.');
        }

        $hasAdvanceBooker = Booking::where('room_id', $activeBooking->room_id)
            ->where('booking_type', 'advance')
            ->whereIn('status', ['pending', 'under_review', 'approved'])
            ->exists();

        if ($hasAdvanceBooker) {
            return redirect()
                ->route('tenant.renewal.index')
                ->with('warning', 'Perpanjangan tidak dapat diubah karena kamar sudah memiliki penghuni baru yang disetujui.');
        }

        $hasWaitingList = \App\Models\WaitingList::hasActiveForRoom($activeBooking->room_id);

        if ($hasWaitingList) {
            return redirect()
                ->route('tenant.renewal.index')
                ->with('warning', 'Perpanjangan tidak dapat diubah karena kamar sudah memiliki calon penghuni dalam daftar tunggu.');
        }

        if (!empty($activeBooking->end_date)) {
            $endDate = Carbon::parse($activeBooking->end_date);
        } elseif ($activeBooking->check_in_date && $activeBooking->duration_months) {
            $endDate = Carbon::parse($activeBooking->check_in_date)
                ->addMonths($activeBooking->duration_months);
        } else {
            $endDate = null;
        }

        if ($endDate && now()->diffInDays($endDate, false) < 10) {
            return redirect()
                ->route('tenant.renewal.index')
                ->with('error', 'Perpanjangan tidak dapat diubah karena sudah melewati batas waktu pengajuan (10 hari sebelum masa sewa berakhir).');
        }

        $activeBooking->computed_end_date = $endDate;

        $activeServices = [];
        if ($activeBooking->additional_services) {
            $raw            = is_array($activeBooking->additional_services)
                ? $activeBooking->additional_services
                : (array) $activeBooking->additional_services;
            $activeServices = array_keys($raw);
        }

        $renewalServices = [];
        if ($renewal->additional_services) {
            $raw             = is_array($renewal->additional_services)
                ? $renewal->additional_services
                : (array) $renewal->additional_services;
            $renewalServices = array_keys($raw);
        }

        $SERVICES  = $this->getServiceCatalog();
        $roomPrice = $activeBooking->room->price ?? 0;

        return view('tenant.renewal.edit', compact(
            'renewal',
            'activeBooking',
            'endDate',
            'activeServices',
            'renewalServices',
            'SERVICES',
            'roomPrice'
        ));
    }

    public function update(Request $request, $id)
    {
        $renewal = Renewal::with(['room', 'booking'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        if ($renewal->status !== 'pending_admin') {
            return back()->with('warning', 'Perpanjangan tidak dapat diubah karena sudah diproses.');
        }

        $catalog     = $this->getServiceCatalog();
        $validKeys   = array_keys($catalog);

        $request->validate([
            'renewal_months' => 'required|integer|in:1,2,3,4,5,6,9,12',
            'services'       => 'nullable|array',
            'services.*'     => ['string', 'in:' . implode(',', $validKeys)],
        ]);

        $user = auth()->user();

        $activeBooking = Booking::with('room')
            ->where('user_id', $user->id)
            ->whereIn('status', ['approved', 'active'])
            ->latest()
            ->firstOrFail();

        if (!empty($activeBooking->end_date)) {
            $currentEndDate = Carbon::parse($activeBooking->end_date);
        } elseif ($activeBooking->check_in_date && $activeBooking->duration_months) {
            $currentEndDate = Carbon::parse($activeBooking->check_in_date)
                ->addMonths($activeBooking->duration_months);
        } else {
            return back()->with('error', 'Tidak dapat menentukan tanggal berakhir sewa.');
        }

        $newEndDate       = $currentEndDate->copy()->addMonths($request->renewal_months);
        $roomPrice        = $activeBooking->room->price ?? 0;
        $selectedServices = $request->services ?? [];

        $serviceMonthly         = 0;
        $additionalServicesData = [];

        foreach ($selectedServices as $key) {
            if (isset($catalog[$key])) {
                $serviceMonthly               += $catalog[$key]['price'];
                $additionalServicesData[$key]  = [
                    'label' => $catalog[$key]['label'],
                    'price' => $catalog[$key]['price'],
                ];
            }
        }

        $totalAmount = ($roomPrice + $serviceMonthly) * $request->renewal_months;

        $renewal->update([
            'duration_months'        => $request->renewal_months,
            'amount'                 => $totalAmount,
            'service_amount_monthly' => $serviceMonthly,
            'additional_services'    => $additionalServicesData,
            'new_end_date'           => $newEndDate,
            'current_end_date'       => $currentEndDate,
        ]);

        $roomNumber = $activeBooking->room->room_number ?? '-';
        $totalFmt   = 'Rp ' . number_format($totalAmount, 0, ',', '.');
        $newEndFmt  = $newEndDate->format('d M Y');
        $durasi     = $request->renewal_months;

        $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))
            ->where('is_active', true)
            ->get();

        foreach ($admins as $admin) {
            InAppNotification::send(
                $admin->id,
                'renewal_updated',
                'Perpanjangan Diperbarui — Kamar ' . $roomNumber,
                $user->name . ' memperbarui pengajuan perpanjangan kamar ' . $roomNumber .
                    ' menjadi ' . $durasi . ' bulan (hingga ' . $newEndFmt . '). Total: ' . $totalFmt . '.',
                route('admin.renewals.index'),
                ['user_id' => $user->id, 'room_id' => $activeBooking->room_id]
            );

            if ($admin->phone) {
                try {
                    $waMsg = "Perpanjangan Diperbarui\n\n"
                        . "Penghuni      : {$user->name}\n"
                        . "Kamar         : {$roomNumber}\n"
                        . "Durasi Baru   : {$durasi} bulan\n"
                        . "Sewa Hingga   : {$newEndFmt}\n"
                        . "Total Tagihan : {$totalFmt}\n"

                    (new \App\Services\WhatsAppService())->send($admin->phone, $waMsg);
                } catch (\Throwable $e) {
                    Log::warning('[RenewalController] WA update (admin ' . $admin->id . '): ' . $e->getMessage());
                }
            }
        }

        return redirect()
            ->route('tenant.renewal.show', $renewal->id)
            ->with('success', 'Pengajuan perpanjangan berhasil diperbarui. Admin akan mereview ulang.');
    }

    public function destroy($id)
    {
        $renewal = Renewal::where('user_id', auth()->id())->findOrFail($id);

        if ($renewal->status !== 'pending_admin') {
            return back()->with('warning', 'Perpanjangan tidak dapat dihapus karena sudah diproses atau dibayar.');
        }

        $user       = auth()->user();
        $roomNumber = $renewal->room?->room_number ?? '-';

        $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))
            ->where('is_active', true)
            ->get();

        foreach ($admins as $admin) {
            InAppNotification::send(
                $admin->id,
                'renewal_cancelled_by_tenant',
                'Pengajuan Perpanjangan Dibatalkan',
                $user->name . ' membatalkan pengajuan perpanjangan kamar ' . $roomNumber . '.',
                route('admin.renewals.index'),
                ['user_id' => $user->id]
            );
        }

        $renewal->delete();

        return redirect()
            ->route('tenant.renewal.index')
            ->with('success', 'Pengajuan perpanjangan berhasil dibatalkan.');
    }

    public function initiate(Request $request)
    {
        $catalog   = $this->getServiceCatalog();
        $validKeys = array_keys($catalog);

        $request->validate([
            'renewal_months' => 'required|integer|in:1,2,3,4,5,6,9,12',
            'services'       => 'nullable|array',
            'services.*'     => ['string', 'in:' . implode(',', $validKeys)],
            'payment_method' => 'nullable|in:fullpay,monthly',
        ]);

        $user = auth()->user();

        $activeBookingCheck = Booking::with('room')
            ->where('user_id', $user->id)
            ->whereIn('status', ['approved', 'active'])
            ->latest()
            ->first();

        if ($activeBookingCheck) {
            $hasBlocker = Booking::where('room_id', $activeBookingCheck->room_id)
                ->where('booking_type', 'advance')
                ->whereIn('status', ['pending', 'under_review', 'approved'])
                ->exists()
                ||
                \App\Models\WaitingList::hasActiveForRoom($activeBookingCheck->room_id);

            if ($hasBlocker) {
                return back()->with('warning', 'Perpanjangan tidak dapat dilakukan karena kamar Anda sudah memiliki calon penghuni baru.');
            }

            if (!empty($activeBookingCheck->end_date)) {
                $endDateCheck = Carbon::parse($activeBookingCheck->end_date);
            } elseif ($activeBookingCheck->check_in_date && $activeBookingCheck->duration_months) {
                $endDateCheck = Carbon::parse($activeBookingCheck->check_in_date)
                    ->addMonths($activeBookingCheck->duration_months);
            } else {
                $endDateCheck = null;
            }

            if ($endDateCheck && now()->diffInDays($endDateCheck, false) < 10) {
                return back()->with('error', 'Pengajuan perpanjangan sudah melewati batas waktu. Perpanjangan harus diajukan paling lambat 10 hari sebelum masa sewa berakhir.');
            }
        }

        $alreadyPending = Renewal::where('user_id', $user->id)
            ->whereIn('status', ['pending_admin', 'pending_payment'])
            ->exists();

        if ($alreadyPending) {
            return back()->with('warning', 'Anda sudah memiliki perpanjangan yang sedang diproses.');
        }

        $activeBooking = Booking::with('room')
            ->where('user_id', $user->id)
            ->whereIn('status', ['approved', 'active'])
            ->latest()
            ->firstOrFail();

        if (!empty($activeBooking->end_date)) {
            $currentEndDate = Carbon::parse($activeBooking->end_date);
        } elseif ($activeBooking->check_in_date && $activeBooking->duration_months) {
            $currentEndDate = Carbon::parse($activeBooking->check_in_date)
                ->addMonths($activeBooking->duration_months);
        } else {
            return back()->with('error', 'Tidak dapat menentukan tanggal berakhir sewa. Hubungi admin.');
        }

        $newEndDate       = $currentEndDate->copy()->addMonths($request->renewal_months);
        $roomPrice        = $activeBooking->room->price ?? 0;
        $selectedServices = $request->services ?? [];

        $serviceMonthly         = 0;
        $additionalServicesData = [];

        foreach ($selectedServices as $key) {
            if (isset($catalog[$key])) {
                $serviceMonthly               += $catalog[$key]['price'];
                $additionalServicesData[$key]  = [
                    'label' => $catalog[$key]['label'],
                    'price' => $catalog[$key]['price'],
                ];
            }
        }

        $totalMonthly = $roomPrice + $serviceMonthly;
        $totalAmount  = $totalMonthly * $request->renewal_months;

        DB::transaction(function () use (
            $user, $activeBooking, $request,
            $newEndDate, $currentEndDate,
            $totalAmount, $serviceMonthly, $additionalServicesData
        ) {
            $renewal = Renewal::create([
                'user_id'                => $user->id,
                'room_id'                => $activeBooking->room_id,
                'booking_id'             => $activeBooking->id,
                'duration_months'        => $request->renewal_months,
                'amount'                 => $totalAmount,
                'service_amount_monthly' => $serviceMonthly,
                'additional_services'    => $additionalServicesData,
                'status'                 => 'pending_admin',
                'new_end_date'           => $newEndDate,
                'current_end_date'       => $currentEndDate,
                'payment_method'         => $request->input('payment_method', 'fullpay'),
            ]);

            $activeBooking->update([
                'renewal_id'             => $renewal->id,
                'renewal_payment_method' => $renewal->payment_method,
                'renewal_period_start'   => $currentEndDate->copy()->addDay()->toDateString(),
            ]);
        });

        $roomNumber = $activeBooking->room->room_number ?? '-';
        $totalFmt   = 'Rp ' . number_format($totalAmount, 0, ',', '.');
        $newEndFmt  = $newEndDate->format('d M Y');
        $durasi     = $request->renewal_months;

        $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))
            ->where('is_active', true)
            ->get();

        foreach ($admins as $admin) {
            InAppNotification::send(
                $admin->id,
                'renewal_requested',
                'Pengajuan Perpanjangan Sewa — Kamar ' . $roomNumber,
                $user->name . ' mengajukan perpanjangan kamar ' . $roomNumber .
                    ' selama ' . $durasi . ' bulan' .
                    ' (hingga ' . $newEndFmt . '). Total: ' . $totalFmt . '.',
                route('admin.renewals.index'),
                ['user_id' => $user->id, 'room_id' => $activeBooking->room_id]
            );

            if ($admin->phone) {
                try {
                    $waAdmin = "Pengajuan Perpanjangan Sewa\n\n"
                        . "Penghuni      : {$user->name}\n"
                        . "Kamar         : {$roomNumber}\n"
                        . "Durasi Tambah : {$durasi} bulan\n"
                        . "Sewa Hingga   : {$newEndFmt}\n"
                        . "Total Tagihan : {$totalFmt}\n"
                        . "Waktu         : " . now()->format('d M Y') . " \n\n"
                        . "Silakan review dan setujui di dashboard admin.";

                    (new \App\Services\WhatsAppService())->send($admin->phone, $waAdmin);
                } catch (\Throwable $e) {
                    Log::warning('[RenewalController] WA initiate (admin ' . $admin->id . '): ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('tenant.renewal.index')
            ->with('success',
                'Pengajuan perpanjangan ' . $durasi . ' bulan berhasil dikirim! ' .
                'Masa sewa baru akan berakhir pada <strong>' . $newEndFmt . '</strong>. ' .
                'Admin akan mereview dalam 1×24 jam.'
            );
    }

    public function invoice($renewalId)
    {
        $renewal = Renewal::with(['room', 'invoice.payments'])
            ->where('user_id', auth()->id())
            ->findOrFail($renewalId);

        $invoice = $renewal->invoice;

        if (!$invoice || $renewal->status === 'pending_admin') {
            return redirect()
                ->route('tenant.renewal.show', $renewal->id)
                ->with('info', 'Tagihan baru tersedia setelah admin menyetujui perpanjangan.');
        }

        $payments = $invoice->payments()->latest()->get();

        return view('tenant.renewal.invoice', compact('renewal', 'invoice', 'payments'));
    }

    public function payMidtrans($renewalId)
    {
        $renewal = Renewal::with(['room', 'invoice', 'user'])
            ->where('user_id', auth()->id())
            ->findOrFail($renewalId);

        $invoice = $renewal->invoice;

        if (!$invoice) {
            return redirect()
                ->route('tenant.renewal.show', $renewal->id)
                ->with('warning', 'Invoice belum tersedia untuk perpanjangan ini.');
        }

        if ($invoice->status === 'paid') {
            return redirect()
                ->route('tenant.renewal.invoice', $renewal->id)
                ->with('info', 'Invoice ini sudah lunas. Tidak perlu melakukan pembayaran lagi.');
        }

        if ($invoice->status === 'pending_verification') {
            return redirect()
                ->route('tenant.renewal.invoice', $renewal->id)
                ->with('info', 'Pembayaran Anda sedang dalam proses verifikasi. Mohon tunggu konfirmasi admin.');
        }

        if (!in_array($invoice->status, ['unpaid', 'overdue'])) {
            return redirect()
                ->route('tenant.renewal.invoice', $renewal->id)
                ->with('warning', 'Status invoice tidak valid untuk melakukan pembayaran.');
        }

        return view('tenant.renewal.pay-midtrans', compact('renewal', 'invoice'));
    }

    public function snapToken(Request $request, $renewalId)
    {
        $renewal = Renewal::with(['room', 'invoice', 'user'])
            ->where('user_id', auth()->id())
            ->findOrFail($renewalId);

        $invoice = $renewal->invoice;

        abort_if(!$invoice, 404);
        abort_if(
            !in_array($invoice->status, ['unpaid', 'overdue']) || $invoice->status === 'pending_verification',
            422,
            'Invoice tidak dapat dibayar.'
        );

        $request->validate([
            'method' => 'required|in:qris,bank',
            'bank'   => 'nullable|in:bca_va,bni_va,bri_va,permata_va,other_va',
        ]);

        $existingPayment = $invoice->payments()
            ->where('status', 'initiated')
            ->where('method', 'midtrans')
            ->whereNotNull('snap_token')
            ->latest()
            ->first();

        if ($existingPayment) {
            return response()->json(['snap_token' => $existingPayment->snap_token]);
        }

        $this->configureMidtrans();

        $method  = $request->input('method');
        $bank    = $request->input('bank');
        $orderId = 'RNW-' . $renewal->id . '-' . now()->format('YmdHis') . '-' . random_int(1000, 9999);

        $payableAmount = (int) $invoice->total_amount;

        $items = [];

        $roomMonthly = $renewal->room?->price ?? 0;
        if ($roomMonthly > 0) {
            $items[] = [
                'id'       => 'ROOM-' . $renewal->room_id,
                'price'    => $roomMonthly,
                'quantity' => $renewal->duration_months,
                'name'     => 'Sewa Kamar ' . ($renewal->room?->room_number ?? '-') .
                              ' (' . $renewal->duration_months . ' bln)',
            ];
        }

        if ($renewal->service_amount_monthly > 0) {
            $items[] = [
                'id'       => 'SVC-RNW-' . $renewal->id,
                'price'    => (int) $renewal->service_amount_monthly,
                'quantity' => $renewal->duration_months,
                'name'     => 'Layanan Tambahan (' . $renewal->duration_months . ' bln)',
            ];
        }

        if (empty($items)) {
            $items[] = [
                'id'       => 'RENEWAL-' . $renewal->id,
                'price'    => $payableAmount,
                'quantity' => 1,
                'name'     => 'Perpanjangan Sewa ' . ($renewal->room?->room_number ?? '-') .
                              ' (' . $renewal->duration_months . ' bln)',
            ];
        }

        $itemsTotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $items));
        if ($itemsTotal !== $payableAmount) {
            $diff = $payableAmount - $itemsTotal;
            $items[count($items) - 1]['price'] += (int) round($diff / max(1, $items[count($items) - 1]['quantity']));
        }

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $payableAmount,
            ],
            'customer_details' => [
                'first_name' => $renewal->user?->name ?? 'Penghuni',
                'email'      => $renewal->user?->email ?? '',
                'phone'      => $renewal->user?->phone ?? '',
            ],
            'item_details' => $items,
            'callbacks'    => [
                'finish' => route('tenant.renewal.invoice', $renewal->id),
            ],
        ];

        if ($method === 'qris') {
            $params['enabled_payments'] = ['other_qris', 'gopay', 'shopeepay', 'dana'];
        } elseif ($method === 'bank') {
            $bankMap = [
                'bca_va'     => 'bca_va',
                'bni_va'     => 'bni_va',
                'bri_va'     => 'bri_va',
                'permata_va' => 'permata_va',
                'other_va'   => 'other_va',
            ];
            $params['enabled_payments'] = [$bankMap[$bank] ?? 'other_va'];
        }

        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            Log::error('[RenewalSnapToken] ' . $e->getMessage(), [
                'renewal_id' => $renewal->id,
                'params'     => $params,
            ]);
            return response()->json([
                'error' => 'Gagal menghubungi Midtrans: ' . $e->getMessage(),
            ], 500);
        }

        try {
            $invoice->payments()->create([
                'user_id'           => $renewal->user_id,
                'payment_code'      => $orderId,
                'method'            => 'midtrans',
                'amount'            => $payableAmount,
                'status'            => 'initiated',
                'snap_token'        => $snapToken,
                'midtrans_order_id' => $orderId,
            ]);
        } catch (\Exception $e) {
            Log::error('[RenewalSnapToken] Gagal simpan payment draft: ' . $e->getMessage(), [
                'renewal_id' => $renewal->id,
                'order_id'   => $orderId,
            ]);
        }

        return response()->json(['snap_token' => $snapToken]);
    }

    public function respond(Request $request, $bookingId)
    {
        $catalog   = $this->getServiceCatalog();
        $validKeys = array_keys($catalog);

        $request->validate([
            'renewal_months' => 'required|integer|in:1,2,3,4,5,6,9,12',
            'services'       => 'nullable|array',
            'services.*'     => ['string', 'in:' . implode(',', $validKeys)],
            'payment_method' => 'nullable|in:fullpay,monthly',
        ]);

        $advanceBooking = Booking::with(['room', 'user'])->findOrFail($bookingId);

        if ((int) $advanceBooking->advance_for_user_id !== (int) auth()->id()) {
            abort(403);
        }

        $currentUser = auth()->user();
        $admins      = User::whereHas('role', fn($q) => $q->whereIn('name', ['admin', 'owner']))->get();
        $room        = $advanceBooking->room;

        if ($request->decision === 'renew') {
            DB::transaction(function () use ($advanceBooking) {
                $advanceBooking->update([
                    'status'               => 'cancelled',
                    'renewal_status'       => 'will_renew',
                    'renewal_responded_at' => now(),
                    'admin_notes'          => 'Dibatalkan otomatis: penghuni aktif memilih perpanjang sewa.',
                ]);
            });

            InAppNotification::send(
                $advanceBooking->user_id,
                'booking_cancelled_renewal',
                'Booking Dibatalkan',
                'Mohon maaf, booking kamar ' . $room?->room_number .
                    ' (' . $advanceBooking->booking_code . ') dibatalkan karena ' .
                    'penghuni aktif memilih untuk memperpanjang masa sewanya.',
                null,
                ['booking_id' => $advanceBooking->id]
            );

            foreach ($admins as $admin) {
                InAppNotification::send(
                    $admin->id,
                    'renewal_intent_noted',
                    'Penghuni Akan Perpanjang Sewa',
                    $currentUser->name . ' menyatakan ingin memperpanjang sewa kamar ' .
                        $room?->room_number . '. Menunggu penghuni mengisi form perpanjangan.',
                    route('admin.waiting-list.index'),
                    ['room_id' => $room?->id]
                );
            }

            return redirect()
                ->route('tenant.renewal.index')
                ->with('success', 'Terima kasih! Advance booking telah dibatalkan. Silakan isi form perpanjangan sewa di bawah ini.');
        }

        $advanceBooking->update([
            'renewal_status'       => 'wont_renew',
            'renewal_responded_at' => now(),
        ]);

        InAppNotification::send(
            $advanceBooking->user_id,
            'booking_processing',
            'Booking Sedang Diproses',
            'Penghuni aktif kamar ' . $room?->room_number . ' tidak akan memperpanjang sewa. ' .
                'Admin sedang memproses booking Anda (' . $advanceBooking->booking_code . ').',
            null,
            ['booking_id' => $advanceBooking->id]
        );

        foreach ($admins as $admin) {
            InAppNotification::send(
                $admin->id,
                'renewal_declined',
                'Penghuni Tidak Perpanjang — Proses Advance Booking',
                $currentUser->name . ' tidak akan memperpanjang sewa kamar ' . $room?->room_number .
                    '. Advance booking ' . $advanceBooking->booking_code . ' siap diproses.',
                route('admin.bookings.show', $advanceBooking->id),
                ['booking_id' => $advanceBooking->id]
            );
        }

        return redirect()
            ->route('tenant.dashboard')
            ->with('info', 'Keputusan Anda telah dicatat. Admin akan memproses transisi kamar.');
    }

    public function midtransWebhook(Request $request)
    {
        return response()->json(['status' => 'ignored']);
    }

    private function configureMidtrans(): void
    {
        MidtransConfig::$serverKey    = config('midtrans.server_key');
        MidtransConfig::$isProduction = config('midtrans.is_production');
        MidtransConfig::$isSanitized  = true;
        MidtransConfig::$is3ds        = true;
    }
}