<?php

namespace App\Services;

use App\Http\Controllers\Auth\LoginController;
use App\Models\Booking;
use App\Models\InAppNotification;
use App\Models\Invoice;
use App\Models\Renewal;
use App\Models\Role;
use App\Models\Room;
use App\Models\User;
use App\Models\WaitingList;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    private WhatsAppService $wa;

    public function __construct()
    {
        $this->wa = new WhatsAppService();
    }

    public function handleInvoicePaidNotifications(Invoice $invoice): void
    {
        $invoice->loadMissing(['user.role', 'room', 'booking', 'payments']);

        $invoiceNum  = $invoice->invoice_number ?? '';
        $invoiceType = $invoice->invoice_type ?? '';

        $isRenewal = str_starts_with($invoiceNum, 'INV-RNW-')
                     || !empty($invoice->renewal_id)
                     || $invoiceType === 'renewal';

        $isDP = str_starts_with($invoiceNum, 'INV-DP-')
                || $invoiceType === 'waiting_list_dp';

        $isSisa = str_starts_with($invoiceNum, 'INV-SISA-')
                  || $invoiceType === 'waiting_list_remaining';

        Log::info('[NotifService] handleInvoicePaidNotifications', [
            'invoice_id'   => $invoice->id,
            'invoice_num'  => $invoiceNum,
            'invoice_type' => $invoiceType,
            'renewal_id'   => $invoice->renewal_id ?? null,
            'is_renewal'   => $isRenewal,
            'is_dp'        => $isDP,
            'is_sisa'      => $isSisa,
        ]);

        if ($isRenewal) {
            $this->handleRenewalPaidNotifications($invoice);
            return;
        }

        if ($isDP) {
            $this->handleDpPaidNotifications($invoice);
            return;
        }

        if ($isSisa) {
            $this->handleSisaPaidNotifications($invoice);
            return;
        }

        $this->handleRegularPaidNotifications($invoice);
    }

    private function handleDpPaidNotifications(Invoice $invoice): void
    {
        $wl = $this->lookupWaitingList($invoice);

        if ($wl && !$invoice->waiting_list_id) {
            $invoice->update(['waiting_list_id' => $wl->id]);
        }

        if ($wl && (!$wl->dp_paid || $wl->dp_paid <= 0)) {
            $wl->update(['dp_paid' => $invoice->total_amount]);
            $wl->refresh();
        }

        $roomNumber = $invoice->room?->room_number ?? '-';
        $dpFmt      = 'Rp ' . number_format($invoice->total_amount, 0, ',', '.');
        $userName   = $invoice->user?->name ?? 'Calon Penghuni';

        InAppNotification::send(
            $invoice->user_id,
            'dp_payment_verified',
            ' DP Waiting List Diterima',
            "DP Anda sebesar {$dpFmt} untuk kamar {$roomNumber} sudah diterima. Posisi antrian Anda aman.",
            route('prospective.invoices.index')
        );

        $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))
            ->where('is_active', true)->get();
        foreach ($admins as $admin) {
            InAppNotification::send(
                $admin->id,
                'dp_payment_received',
                " DP Waiting List Masuk — Kamar {$roomNumber}",
                "{$userName} membayar DP {$dpFmt} untuk waiting list kamar {$roomNumber}.",
                route('admin.invoices.show', $invoice->id),
                ['invoice_id' => $invoice->id]
            );
        }

        if ($invoice->user?->phone) {
            try {
                if ($wl) {
                    $this->wa->sendWaitingListDpPaid($invoice->user->phone, [
                        'name'            => $userName,
                        'room'            => $roomNumber,
                        'dp_paid'         => $dpFmt,
                        'position'        => $wl->position,
                        'waiting_list_id' => $wl->id,
                    ]);
                } else {
                    $this->wa->sendPaymentConfirmed($invoice->user->phone, [
                        'name'           => $userName,
                        'invoice_number' => $invoice->invoice_number,
                        'amount'         => $dpFmt,
                        'room'           => $roomNumber,
                        'verified_at'    => now()->format('d M Y') . '',
                    ]);
                }
            } catch (\Throwable $e) {
                Log::warning('[NotifService] WA DP paid: ' . $e->getMessage());
            }
        }
    }

    private function handleSisaPaidNotifications(Invoice $invoice): void
    {
        $wl            = $this->lookupWaitingList($invoice);
        $user          = $invoice->user;
        $roomId        = $invoice->room_id;
        $upgraded      = false;
        $oldTenantName = null;

        $oldTenantUserId = $wl?->booking?->advance_for_user_id
            ?? $invoice->booking?->advance_for_user_id;

        DB::transaction(function () use ($invoice, $wl, $user, $roomId, $oldTenantUserId, &$upgraded, &$oldTenantName) {
            $oldBookings = Booking::where('room_id', $roomId)
                ->whereIn('status', ['approved', 'active'])
                ->where('user_id', '!=', $user?->id)
                ->with('user.role')
                ->get();

            foreach ($oldBookings as $oldBooking) {
                $oldTenantUser = $oldBooking->user;
                $oldBooking->update(['status' => 'completed', 'end_date' => now()->toDateString()]);
                Log::info('[NotifService] Booking lama #' . $oldBooking->id . ' → completed');

                if ($oldTenantUser) {
                    $stillHasRoom = Booking::where('user_id', $oldTenantUser->id)
                        ->whereIn('status', ['approved', 'active'])
                        ->where('id', '!=', $oldBooking->id)
                        ->exists();

                    if (!$stillHasRoom && $oldTenantUser->role?->name === 'tenant') {
                        $prospectiveRole = Role::where('name', 'prospective_tenant')->first();
                        if ($prospectiveRole) {
                            $oldTenantUser->update(['role_id' => $prospectiveRole->id]);
                        }
                        $oldTenantName = $oldTenantUser->name;

                        InAppNotification::send(
                            $oldTenantUser->id,
                            'tenancy_ended',
                            ' Masa Sewa Kamar Telah Berakhir',
                            'Masa sewa kamar ' . ($invoice->room?->room_number ?? '-') . ' Anda telah resmi berakhir.',
                            route('home')
                        );

                        try { $this->notifyTenancyEnded($oldTenantUser, $invoice->room); }
                        catch (\Throwable $e) { Log::warning('[NotifService] notifyTenancyEnded: ' . $e->getMessage()); }
                    }
                }
            }

            if ($oldTenantUserId && !$oldBookings->firstWhere('user_id', $oldTenantUserId)) {
                $directOldTenant = User::with('role')->find($oldTenantUserId);
                if ($directOldTenant && $directOldTenant->role?->name === 'tenant') {
                    $stillHasRoom = Booking::where('user_id', $oldTenantUserId)
                        ->whereIn('status', ['approved', 'active'])->exists();
                    if (!$stillHasRoom) {
                        $prospectiveRole = Role::where('name', 'prospective_tenant')->first();
                        if ($prospectiveRole) { $directOldTenant->update(['role_id' => $prospectiveRole->id]); }
                        $oldTenantName = $oldTenantName ?? $directOldTenant->name;
                    }
                }
            }

            if ($user && in_array($user->role?->name, ['prospective_tenant', 'prospective'])) {
                $tenantRole = Role::where('name', 'tenant')->firstOrFail();
                $user->update(['role_id' => $tenantRole->id, 'is_active' => true]);
                try { LoginController::generateAndSendOtp($user->email, 'login', $user->name); }
                catch (\Throwable $e) { Log::warning('[NotifService] OTP gagal: ' . $e->getMessage()); }
                try { $this->notifyTenantUpgraded($user, $invoice->room); }
                catch (\Throwable $e) { Log::warning('[NotifService] notifyTenantUpgraded: ' . $e->getMessage()); }
                $upgraded = true;
                Log::info('[NotifService] User #' . $user->id . ' upgraded → tenant');
            }

            if ($invoice->booking) {
                $bookingUpdate = ['status' => 'active', 'room_id' => $roomId];
                if (empty($invoice->booking->check_in_date)) {
                    $bookingUpdate['check_in_date'] = now()->toDateString();
                }
                $invoice->booking->update($bookingUpdate);
            }

            WaitingList::where('room_id', $roomId)
                ->whereIn('status', ['waiting', 'notified'])
                ->update(['status' => 'converted', 'converted_at' => now()]);

            if ($wl && !$invoice->waiting_list_id) {
                $invoice->update(['waiting_list_id' => $wl->id]);
            }

            $invoice->room?->update(['status' => 'occupied']);
            Log::info('[NotifService] Pelunasan WL selesai. Room #' . $roomId . ' → occupied');
        });

        if ($oldTenantUserId) {
            InAppNotification::send(
                $oldTenantUserId,
                'room_handover_complete',
                ' Penghuni Baru Sudah Aktif',
                'Kamar ' . ($invoice->room?->room_number ?? '-') . ' kini resmi ditempati penghuni baru.',
                route('home'),
                ['room_id' => $roomId]
            );
            try { $this->notifyOldTenantRoomHandover($oldTenantUserId, $invoice->room); }
            catch (\Throwable $e) { Log::warning('[NotifService] notifyOldTenantRoomHandover: ' . $e->getMessage()); }
        }

        InAppNotification::send(
            $invoice->user_id,
            'tenancy_activated',
            ' Selamat! Anda Resmi Menjadi Penghuni',
            'Pelunasan untuk kamar ' . ($invoice->room?->room_number ?? '-') . ' dikonfirmasi. Hubungi admin untuk serah terima kunci.',
            route('tenant.dashboard')
        );

        $totalFmt = 'Rp ' . number_format($invoice->total_amount, 0, ',', '.');
        $roomNum  = $invoice->room?->room_number ?? '-';

        if ($invoice->user?->phone) {
            try {
                $this->sendWa($invoice->user->phone,
                    "Halo *{$invoice->user->name}*,\n\n *Pelunasan Diterima — Anda Resmi Menjadi Penghuni!*\n\n" .
                    "━━━━━━━━━━━━━━━━\n Kamar      : *{$roomNum}*\n Pelunasan  : *{$totalFmt}*\n" .
                    " Waktu      : " . now()->format('d M Y') . "\n━━━━━━━━━━━━━━━━\n\n" .
                    "Silakan hubungi admin untuk serah terima kunci. Selamat datang! "
                );
            } catch (\Throwable $e) { Log::warning('[NotifService] WA sisa paid: ' . $e->getMessage()); }
        }

        $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->where('is_active', true)->get();
        $msgAdmin = ($invoice->user?->name ?? '-') . " melunasi sewa kamar {$roomNum} ({$totalFmt}). Kamar → Occupied." .
            ($upgraded ? ' Penghuni diupgrade.' : '') .
            ($oldTenantName ? " Penghuni lama: {$oldTenantName} diselesaikan." : '');
        foreach ($admins as $admin) {
            InAppNotification::send(
                $admin->id, 'wl_sisa_paid', " Pelunasan WL Masuk — Kamar {$roomNum}", $msgAdmin,
                route('admin.invoices.show', $invoice->id), ['invoice_id' => $invoice->id]
            );
        }
    }

    private function handleRenewalPaidNotifications(Invoice $invoice): void
    {
        $renewal = null;

        if (!empty($invoice->renewal_id)) {
            $renewal = Renewal::with(['user', 'room', 'booking'])->find($invoice->renewal_id);
            Log::info('[NotifService] Renewal lookup via renewal_id: ' . ($renewal ? 'FOUND' : 'NOT FOUND'), [
                'renewal_id' => $invoice->renewal_id,
            ]);
        }

        if (!$renewal && str_starts_with($invoice->invoice_number ?? '', 'INV-RNW-')) {
            $parts     = explode('-', $invoice->invoice_number);
            $renewalId = isset($parts[3]) ? ltrim($parts[3], '0') : null;

            if ($renewalId && is_numeric($renewalId)) {
                $renewal = Renewal::with(['user', 'room', 'booking'])->find((int) $renewalId);
                Log::info('[NotifService] Renewal lookup via invoice_number parse: ' . ($renewal ? 'FOUND' : 'NOT FOUND'), [
                    'parsed_id' => $renewalId,
                ]);

                if ($renewal && !$invoice->renewal_id) {
                    $invoice->update(['renewal_id' => $renewal->id, 'invoice_type' => 'renewal']);
                }
            }
        }

        if (!$renewal && $invoice->booking_id) {
            $renewal = Renewal::with(['user', 'room', 'booking'])
                ->where('booking_id', $invoice->booking_id)
                ->whereIn('status', ['pending_payment', 'pending_admin', 'approved'])
                ->latest()
                ->first();
            Log::info('[NotifService] Renewal lookup via booking_id: ' . ($renewal ? 'FOUND' : 'NOT FOUND'), [
                'booking_id' => $invoice->booking_id,
            ]);
        }

        if (!$renewal && $invoice->user_id && $invoice->room_id) {
            $renewal = Renewal::with(['user', 'room', 'booking'])
                ->where('user_id', $invoice->user_id)
                ->where('room_id', $invoice->room_id)
                ->whereIn('status', ['pending_payment', 'pending_admin', 'approved'])
                ->latest()
                ->first();
            Log::info('[NotifService] Renewal lookup via user+room fallback: ' . ($renewal ? 'FOUND' : 'NOT FOUND'), [
                'user_id' => $invoice->user_id,
                'room_id' => $invoice->room_id,
            ]);
        }

        if (!$renewal) {
            Log::error('[NotifService] Renewal TIDAK DITEMUKAN setelah 4 strategi', [
                'invoice_id'  => $invoice->id,
                'invoice_num' => $invoice->invoice_number,
                'user_id'     => $invoice->user_id,
                'room_id'     => $invoice->room_id,
            ]);
            $this->notifyAdminRenewalNotFound($invoice);
            return;
        }

        $invoiceUpdates = [];
        if (!$invoice->renewal_id) { $invoiceUpdates['renewal_id'] = $renewal->id; }
        if ($invoice->invoice_type !== 'renewal') { $invoiceUpdates['invoice_type'] = 'renewal'; }
        if (!empty($invoiceUpdates)) { $invoice->update($invoiceUpdates); }

        $booking = $renewal->booking;

        if (!$booking || !in_array($booking->status, ['approved', 'active'])) {
            $booking = Booking::where('user_id', $renewal->user_id)
                ->where('room_id', $renewal->room_id)
                ->whereIn('status', ['approved', 'active'])
                ->latest()
                ->first();
        }

        if (!$booking) {
            Log::error('[NotifService] Booking aktif tidak ditemukan untuk renewal', [
                'renewal_id' => $renewal->id,
                'user_id'    => $renewal->user_id,
                'room_id'    => $renewal->room_id,
            ]);
            $renewal->update(['status' => 'approved', 'approved_at' => now()]);
            $this->notifyAdminRenewalNoBooking($renewal, $invoice);
            return;
        }

        $newEndDate = null;

        if (!empty($renewal->new_end_date)) {
            $newEndDate = Carbon::parse($renewal->new_end_date);
        } else {
            $currentEnd = !empty($booking->end_date)
                ? Carbon::parse($booking->end_date)
                : Carbon::parse($booking->check_in_date)->addMonths($booking->duration_months);

            $newEndDate = $currentEnd->copy()->addMonths($renewal->duration_months);
        }

        DB::transaction(function () use ($renewal, $booking, $newEndDate, $invoice) {
            $bookingUpdateData = [
                'duration_months' => $booking->duration_months + $renewal->duration_months,
                'end_date'        => $newEndDate->toDateString(),
                'status'          => 'active',
            ];

            if (!empty($renewal->additional_services)) {
                $bookingUpdateData['additional_services'] = $renewal->additional_services;
            }

            $booking->update($bookingUpdateData);

            $renewal->update([
                'status'       => 'approved',
                'new_end_date' => $newEndDate->toDateString(),
                'approved_at'  => now(),
            ]);

            Log::info('[NotifService] Renewal #' . $renewal->id . ' → approved. Booking #' . $booking->id .
                ' end_date → ' . $newEndDate->toDateString() .
                ' (+ ' . $renewal->duration_months . ' bln)');
        });

        $newEndFmt  = $newEndDate->format('d M Y');
        $totalFmt   = 'Rp ' . number_format($invoice->total_amount, 0, ',', '.');
        $roomNumber = $renewal->room?->room_number ?? '-';
        $userName   = $renewal->user?->name ?? 'Penghuni';
        $durasi     = $renewal->duration_months;

        InAppNotification::send(
            $renewal->user_id,
            'renewal_final_approved',
            ' Sewa Berhasil Diperpanjang!',
            "Pembayaran perpanjangan kamar {$roomNumber} dikonfirmasi. Sewa diperpanjang {$durasi} bulan hingga {$newEndFmt}.",
            route('tenant.renewal.invoice', $renewal->id)
        );

        if ($renewal->user?->phone) {
            try {
                $this->sendWa($renewal->user->phone,
                    "Halo *{$userName}*,\n\n *Perpanjangan Sewa Berhasil!*\n\n" .
                    "━━━━━━━━━━━━━━━━\n" .
                    " Kamar         : *{$roomNumber}*\n" .
                    " Durasi Tambah : *{$durasi} bulan*\n" .
                    " Sewa Hingga   : *{$newEndFmt}*\n" .
                    " Total Bayar   : *{$totalFmt}*\n" .
                    "━━━━━━━━━━━━━━━━\n\n" .
                    "Terima kasih telah memperpanjang sewa di Urban Kost "
                );
            } catch (\Throwable $e) { Log::warning('[NotifService] WA renewal paid: ' . $e->getMessage()); }
        }

        $oldTenantUserId = $booking->advance_for_user_id ?? null;
        if ($oldTenantUserId && $oldTenantUserId !== $renewal->user_id) {
            $oldTenant = User::find($oldTenantUserId);
            if ($oldTenant) {
                InAppNotification::send(
                    $oldTenant->id,
                    'renewal_completed_old_tenant',
                    ' Penghuni Perpanjang Sewa',
                    "Penghuni kamar {$roomNumber} ({$userName}) memperpanjang sewa hingga {$newEndFmt}.",
                    route('tenant.dashboard')
                );
            }
        }

        $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))
            ->where('is_active', true)->get();

        foreach ($admins as $admin) {
            InAppNotification::send(
                $admin->id,
                'renewal_completed',
                " Perpanjangan Selesai — {$roomNumber}",
                "{$userName} membayar perpanjangan {$durasi} bulan (hingga {$newEndFmt}). Total: {$totalFmt}.",
                route('admin.renewals.show', $renewal->id)
            );

            if ($admin->phone) {
                try {
                    $this->sendWa($admin->phone,
                        " *Perpanjangan Sewa Selesai*\n\n" .
                        "• Penghuni     : *{$userName}*\n" .
                        "• Kamar        : *{$roomNumber}*\n" .
                        "• Durasi       : *{$durasi} bulan*\n" .
                        "• Sewa Hingga  : *{$newEndFmt}*\n" .
                        "• Total Bayar  : *{$totalFmt}*\n" .
                        "• Waktu        : " . now()->format('d M Y') . " \n\n" .
                        "Perpanjangan sewa berhasil diproses."
                    );
                } catch (\Throwable $e) { Log::warning('[NotifService] WA renewal admin #' . $admin->id . ': ' . $e->getMessage()); }
            }
        }
    }

    private function handleRegularPaidNotifications(Invoice $invoice): void
    {
        $user     = $invoice->user;
        $upgraded = false;
        $roomId   = $invoice->room_id;
        $roomNum  = $invoice->room?->room_number ?? '-';
        $totalFmt = 'Rp ' . number_format($invoice->total_amount, 0, ',', '.');

        $roomStillOccupied = Booking::where('room_id', $roomId)
            ->whereIn('status', ['approved', 'active'])
            ->where('user_id', '!=', $invoice->user_id)
            ->exists();

        if ($user && $user->role?->name === 'prospective_tenant' && !$roomStillOccupied) {
            $tenantRole = Role::where('name', 'tenant')->firstOrFail();
            $user->update(['role_id' => $tenantRole->id, 'is_active' => true]);
            try {
                LoginController::generateAndSendOtp($user->email, 'login', $user->name);
                $this->notifyTenantUpgraded($user, $invoice->room);
            } catch (\Throwable $e) { Log::warning('[NotifService] upgrade reguler: ' . $e->getMessage()); }
            $upgraded = true;
        }

        if (!$roomStillOccupied) {
            $invoice->room?->update(['status' => 'occupied']);
            if ($invoice->booking && $invoice->booking->status === 'approved') {
                $invoice->booking->update(['status' => 'active']);
            }
        }

        $showRoute = $this->invoiceRoute($invoice);
        InAppNotification::send(
            $invoice->user_id, 'payment_verified',
            ' Pembayaran Dikonfirmasi — Lunas!',
            "Invoice {$invoice->invoice_number} senilai {$totalFmt} telah lunas.",
            $showRoute, ['invoice_id' => $invoice->id]
        );

        if ($user?->phone) {
            try {
                $this->wa->sendPaymentConfirmed($user->phone, [
                    'name'           => $user->name,
                    'invoice_number' => $invoice->invoice_number,
                    'amount'         => $totalFmt,
                    'period'         => $invoice->period_start?->format('F Y'),
                    'room'           => $roomNum,
                    'verified_at'    => now()->format('d M Y') . '',
                ]);
            } catch (\Throwable $e) { Log::warning('[NotifService] WA reguler paid: ' . $e->getMessage()); }
        }

        $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->where('is_active', true)->get();
        $msgAdmin = ($user?->name ?? '-') . " membayar invoice {$invoice->invoice_number} ({$totalFmt})." .
            ($upgraded ? ' Diupgrade menjadi penghuni aktif.' : '');
        foreach ($admins as $admin) {
            InAppNotification::send(
                $admin->id, 'invoice_paid_auto', " Pembayaran Masuk — {$invoice->invoice_number}", $msgAdmin,
                route('admin.invoices.show', $invoice->id), ['invoice_id' => $invoice->id]
            );
        }
    }

    private function notifyAdminRenewalNotFound(Invoice $invoice): void
    {
        $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->where('is_active', true)->get();
        foreach ($admins as $admin) {
            InAppNotification::send(
                $admin->id,
                'renewal_not_found_after_payment',
                ' Pembayaran Renewal Masuk — Data Renewal Tidak Ditemukan',
                'Invoice ' . $invoice->invoice_number . ' sudah lunas tapi data renewal tidak ditemukan. Mohon proses manual.',
                route('admin.invoices.show', $invoice->id),
                ['invoice_id' => $invoice->id]
            );
        }
    }

    private function notifyAdminRenewalNoBooking(Renewal $renewal, Invoice $invoice): void
    {
        $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->where('is_active', true)->get();
        foreach ($admins as $admin) {
            InAppNotification::send(
                $admin->id,
                'renewal_no_booking',
                ' Renewal Disetujui — Booking Aktif Tidak Ditemukan',
                'Renewal #' . $renewal->id . ' untuk ' . ($renewal->user?->name ?? '-') .
                    ' kamar ' . ($renewal->room?->room_number ?? '-') . ' disetujui tapi booking aktif tidak ditemukan. Cek manual.',
                route('admin.renewals.show', $renewal->id),
                ['renewal_id' => $renewal->id]
            );
        }
    }

    public function lookupWaitingList(Invoice $invoice): ?WaitingList
    {
        if ($invoice->waiting_list_id) {
            $wl = WaitingList::find($invoice->waiting_list_id);
            if ($wl) return $wl;
        }

        if ($invoice->booking_id) {
            $wl = WaitingList::where('booking_id', $invoice->booking_id)
                ->whereNotIn('status', ['cancelled', 'expired'])
                ->latest()->first();
            if ($wl) return $wl;
        }

        $invoiceType = $invoice->invoice_type ?? '';
        if ($invoice->user_id && $invoice->room_id
            && in_array($invoiceType, ['waiting_list_dp', 'waiting_list_remaining'])
        ) {
            $wl = WaitingList::where('user_id', $invoice->user_id)
                ->where('room_id', $invoice->room_id)
                ->whereNotIn('status', ['cancelled', 'expired'])
                ->latest()->first();
            if ($wl) return $wl;
        }

        return null;
    }

    public function notifyNewBooking(\App\Models\Booking $booking): void
    {
        $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))
            ->where('is_active', true)->get();

        foreach ($admins as $admin) {
            InAppNotification::send(
                $admin->id,
                'new_booking',
                $booking->isAdvance() ? ' Advance Booking Masuk' : ' Booking Baru Masuk',
                ($booking->isAdvance() ? '[ADVANCE] ' : '') .
                    $booking->full_name . ' mengajukan booking kamar ' .
                    $booking->room?->room_number . ' — kode: ' . $booking->booking_code,
                route('admin.bookings.show', $booking->id),
                ['booking_id' => $booking->id]
            );

            if ($admin->phone) {
                try {
                    $this->wa->sendNewBookingAdmin($admin->phone, [
                        'name'         => $booking->full_name,
                        'phone'        => $booking->phone,
                        'room'         => $booking->room?->room_number . ' - ' . $booking->room?->room_name,
                        'check_in'     => $booking->check_in_date?->format('d M Y'),
                        'duration'     => $booking->duration_months,
                        'booking_code' => $booking->booking_code,
                        'is_advance'   => $booking->isAdvance(),
                    ]);
                } catch (\Throwable $e) { Log::warning('[WA] notifyNewBooking admin: ' . $e->getMessage()); }
            }
        }

        if ($booking->isAdvance()) {
            $oldTenantUserId = $booking->advance_for_user_id;

            if (!$oldTenantUserId && $booking->room_id) {
                $currentTenant = User::whereHas('role', fn($q) => $q->where('name', 'tenant'))
                    ->where('is_active', true)
                    ->whereHas('bookings', fn($q) => $q
                        ->where('room_id', $booking->room_id)
                        ->whereIn('status', ['approved', 'active'])
                    )->first();
                $oldTenantUserId = $currentTenant?->id;

                if ($oldTenantUserId) {
                    $booking->updateQuietly(['advance_for_user_id' => $oldTenantUserId]);
                }
            }

            if ($oldTenantUserId) {
                $currentTenant = User::find($oldTenantUserId);
                if ($currentTenant) {
                    InAppNotification::send(
                        $currentTenant->id,
                        'renewal_inquiry',
                        ' Konfirmasi Perpanjangan Sewa',
                        'Ada calon penghuni yang ingin menempati kamar Anda (' .
                            ($booking->room?->room_number ?? '-') . ') mulai ' .
                            $booking->check_in_date?->format('d M Y') .
                            '. Apakah Anda memperpanjang sewa?',
                        $this->safeRoute('tenant.renewal.index'),
                        ['booking_id' => $booking->id]
                    );

                    if ($currentTenant->phone) {
                        try {
                            $this->wa->sendRenewalInquiry($currentTenant->phone, [
                                'name'       => $currentTenant->name,
                                'room'       => $booking->room?->room_number,
                                'check_in'   => $booking->check_in_date?->format('d M Y'),
                                'booking_id' => $booking->id,
                            ]);
                        } catch (\Throwable $e) { Log::warning('[WA] notifyNewBooking tenant renewal: ' . $e->getMessage()); }
                    }
                }
            }
        }
    }

    public function notifyBookingApprovedWithInvoice(\App\Models\Booking $booking, Invoice $invoice): void
    {
        if (!$booking->user) return;

        InAppNotification::send(
            $booking->user_id,
            'booking_approved',
            ' Booking Disetujui — Invoice Diterbitkan!',
            'Booking kamar ' . $booking->room?->room_number . ' (kode: ' . $booking->booking_code . ') disetujui. ' .
                'Invoice ' . $invoice->invoice_number . ' senilai Rp ' .
                number_format($invoice->total_amount, 0, ',', '.') .
                ' jatuh tempo ' . $invoice->due_date?->format('d M Y') . '. Segera bayar untuk mengkonfirmasi kamar.',
            route('prospective.invoices.show', $invoice->id),
            ['booking_id' => $booking->id, 'invoice_id' => $invoice->id]
        );

        $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->where('is_active', true)->get();
        foreach ($admins as $admin) {
            InAppNotification::send(
                $admin->id, 'invoice_created_auto', ' Invoice Otomatis Dibuat — ' . $invoice->invoice_number,
                'Booking ' . $booking->booking_code . ' disetujui. Invoice Rp ' .
                    number_format($invoice->total_amount, 0, ',', '.') . ' dikirim ke ' . $booking->user->name . '.',
                route('admin.invoices.show', $invoice->id), ['invoice_id' => $invoice->id]
            );
        }

        if ($booking->user->phone) {
            try {
                if (method_exists($this->wa, 'sendBookingApprovedWithInvoice')) {
                    $this->wa->sendBookingApprovedWithInvoice($booking->user->phone, [
                        'name'           => $booking->user->name,
                        'booking_code'   => $booking->booking_code,
                        'room'           => $booking->room?->room_number . ' - ' . $booking->room?->room_name,
                        'check_in'       => $booking->check_in_date?->format('d M Y'),
                        'invoice_number' => $invoice->invoice_number,
                        'total_amount'   => 'Rp ' . number_format($invoice->total_amount, 0, ',', '.'),
                        'due_date'       => $invoice->due_date?->format('d M Y'),
                    ]);
                } else {
                    $this->wa->sendBookingConfirmation($booking->user->phone, [
                        'name'         => $booking->user->name,
                        'status'       => 'approved',
                        'booking_code' => $booking->booking_code,
                        'room'         => $booking->room?->room_number . ' - ' . $booking->room?->room_name,
                        'check_in'     => $booking->check_in_date?->format('d M Y'),
                        'reason'       => 'Invoice ' . $invoice->invoice_number . ' senilai Rp ' .
                            number_format($invoice->total_amount, 0, ',', '.') . ' telah dikirim. Jatuh tempo: ' . $invoice->due_date?->format('d M Y'),
                    ]);
                }
            } catch (\Throwable $e) { Log::warning('[WA] notifyBookingApprovedWithInvoice: ' . $e->getMessage()); }
        }
    }

    public function notifyBookingApproved(\App\Models\Booking $booking): void
    {
        if (!$booking->user) return;
        InAppNotification::send(
            $booking->user_id, 'booking_approved', ' Booking Disetujui!',
            'Booking kamar ' . $booking->room?->room_number . ' (kode: ' . $booking->booking_code . ') telah disetujui.',
            route('prospective.bookings'), ['booking_id' => $booking->id]
        );
        if ($booking->user->phone) {
            try {
                $this->wa->sendBookingConfirmation($booking->user->phone, [
                    'name' => $booking->user->name, 'status' => 'approved',
                    'booking_code' => $booking->booking_code,
                    'room' => $booking->room?->room_number . ' - ' . $booking->room?->room_name,
                    'check_in' => $booking->check_in_date?->format('d M Y'),
                ]);
            } catch (\Throwable $e) { Log::warning('[WA] notifyBookingApproved: ' . $e->getMessage()); }
        }
    }

    public function notifyBookingRejected(\App\Models\Booking $booking): void
    {
        if (!$booking->user) return;
        InAppNotification::send(
            $booking->user_id, 'booking_rejected', ' Booking Ditolak',
            'Booking kamar ' . $booking->room?->room_number . ' ditolak. Alasan: ' . ($booking->admin_notes ?? '-'),
            route('home') . '#kamar', ['booking_id' => $booking->id]
        );
        if ($booking->user->phone) {
            try {
                $this->wa->sendBookingConfirmation($booking->user->phone, [
                    'name' => $booking->user->name, 'status' => 'rejected',
                    'booking_code' => $booking->booking_code,
                    'room' => $booking->room?->room_number,
                    'check_in' => $booking->check_in_date?->format('d M Y'),
                    'reason' => $booking->admin_notes,
                ]);
            } catch (\Throwable $e) { Log::warning('[WA] notifyBookingRejected: ' . $e->getMessage()); }
        }
    }

    public function notifyWaitingListApproved(WaitingList $wl): void
    {
        if (!$wl->user) return;
        InAppNotification::send(
            $wl->user_id, 'waiting_list_approved', ' Anda Masuk Waiting List!',
            'Permohonan advance booking untuk kamar ' . $wl->room?->room_number .
                ' disetujui. Posisi Anda: #' . $wl->position . '. Segera bayar DP 50% sebesar Rp ' .
                number_format($wl->dp_amount ?? 0, 0, ',', '.') . ' untuk mengamankan posisi.',
            route('prospective.waiting-list.show', $wl->id),
            ['waiting_list_id' => $wl->id, 'room_id' => $wl->room_id]
        );
        $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->where('is_active', true)->get();
        foreach ($admins as $admin) {
            InAppNotification::send(
                $admin->id, 'waiting_list_created', ' Waiting List Baru — Kamar ' . $wl->room?->room_number,
                $wl->user?->name . ' masuk waiting list kamar ' . $wl->room?->room_number .
                    '. DP: Rp ' . number_format($wl->dp_amount ?? 0, 0, ',', '.'),
                route('admin.waiting-list.show', $wl->id), ['waiting_list_id' => $wl->id]
            );
        }
        if ($wl->user->phone) {
            try {
                $this->wa->sendWaitingListApproved($wl->user->phone, [
                    'name'            => $wl->user->name,
                    'room'            => $wl->room?->room_number . ($wl->room?->room_name ? ' - ' . $wl->room->room_name : ''),
                    'dp_amount'       => 'Rp ' . number_format($wl->dp_amount ?? 0, 0, ',', '.'),
                    'dp_percentage'   => '50%',
                    'position'        => $wl->position,
                    'waiting_list_id' => $wl->id,
                    'booking_code'    => $wl->booking?->booking_code,
                    'pay_url'         => route('prospective.waiting-list.pay-dp', $wl->id),
                ]);
            } catch (\Throwable $e) { Log::warning('[WA] notifyWaitingListApproved: ' . $e->getMessage()); }
        }
    }

    public function notifyWaitingListDpPaid(WaitingList $wl): void
    {
        if (!$wl->user) return;
        $roomNumber = $wl->room?->room_number ?? '-';
        $roomLabel  = $wl->room?->room_name ? "{$roomNumber} ({$wl->room->room_name})" : $roomNumber;
        $dpPaidFmt  = 'Rp ' . number_format($wl->dp_paid ?? 0, 0, ',', '.');
        $userName   = $wl->user->name;

        InAppNotification::send(
            $wl->user_id, 'waiting_list_dp_paid', ' DP Waiting List Lunas!',
            "Pembayaran DP kamar {$roomLabel} sebesar {$dpPaidFmt} telah diterima. Posisi Anda di waiting list resmi diamankan.",
            route('prospective.waiting-list.show', $wl->id), ['waiting_list_id' => $wl->id]
        );

        $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->where('is_active', true)->get();
        foreach ($admins as $admin) {
            InAppNotification::send(
                $admin->id, 'waiting_list_dp_received', " DP Waiting List Masuk — Kamar {$roomNumber}",
                "{$userName} membayar DP {$dpPaidFmt} untuk waiting list kamar {$roomLabel}.",
                route('admin.waiting-list.show', $wl->id), ['waiting_list_id' => $wl->id]
            );
            if ($admin->phone) {
                try {
                    $this->sendWa($admin->phone,
                        " *DP Waiting List Masuk*\n\n• Calon Penghuni : *{$userName}*\n" .
                        "• Kamar          : *{$roomLabel}*\n• Nominal DP     : *{$dpPaidFmt}*\n" .
                        "• Posisi WL      : #{$wl->position}\n• Waktu          : " . now()->format('d M Y') . " "
                    );
                } catch (\Throwable $e) { Log::warning('[WA] notifyWaitingListDpPaid admin: ' . $e->getMessage()); }
            }
        }

        if ($wl->user->phone) {
            try {
                $this->wa->sendWaitingListDpPaid($wl->user->phone, [
                    'name' => $userName, 'room' => $roomLabel, 'dp_paid' => $dpPaidFmt,
                    'position' => $wl->position, 'waiting_list_id' => $wl->id,
                ]);
            } catch (\Throwable $e) { Log::warning('[WA] notifyWaitingListDpPaid user: ' . $e->getMessage()); }
        }
    }

    public function notifyRoomAvailableForWaitingList(WaitingList $wl, ?float $sisaAmount = null): void
    {
        if (!$wl->user) return;
        $remaining  = $sisaAmount ?? $wl->remainingAmount();
        $roomNumber = $wl->room?->room_number ?? '-';
        $roomLabel  = $wl->room?->room_name ? "{$roomNumber} ({$wl->room->room_name})" : $roomNumber;

        InAppNotification::send(
            $wl->user_id, 'room_available_for_wl', ' Kamar ' . $roomNumber . ' Sudah Tersedia!',
            'Kamar yang Anda tunggu sudah tersedia! ' .
                ($remaining > 0 ? 'Lunasi sisa pembayaran sebesar Rp ' . number_format($remaining, 0, ',', '.') . ' untuk resmi menempati kamar.' : 'Segera hubungi admin untuk aktivasi.'),
            route('prospective.waiting-list.show', $wl->id), ['waiting_list_id' => $wl->id, 'room_id' => $wl->room_id]
        );

        if ($wl->user->phone) {
            try {
                $this->wa->sendRoomAvailableWaitingList($wl->user->phone, [
                    'name' => $wl->user->name, 'room' => $roomLabel,
                    'dp_paid' => 'Rp ' . number_format($wl->dp_paid ?? 0, 0, ',', '.'),
                    'remaining' => 'Rp ' . number_format($remaining, 0, ',', '.'),
                    'duration' => $wl->booking?->duration_months ?? '-',
                    'waiting_list_id' => $wl->id,
                    'pay_url' => route('prospective.waiting-list.pay-dp', $wl->id),
                ]);
            } catch (\Throwable $e) { Log::warning('[WA] notifyRoomAvailableForWaitingList: ' . $e->getMessage()); }
        }
    }

    public function notifyWaitingListCancelled(WaitingList $wl): void
    {
        if (!$wl->user) return;

        $hasPenalty  = ($wl->penalty_amount ?? 0) > 0;
        $penaltyFmt  = $hasPenalty ? 'Rp ' . number_format($wl->penalty_amount, 0, ',', '.') : null;
        $roomNumber  = $wl->room?->room_number ?? '-';
        $roomLabel   = $wl->room?->room_name ? "{$roomNumber} ({$wl->room->room_name})" : $roomNumber;
        $userName    = $wl->user->name;
        $reason      = $wl->cancellation_reason ?? '-';

        $userBody = $hasPenalty
            ? "Posisi waiting list Anda untuk Kamar {$roomLabel} telah dibatalkan. Denda 10% dari DP = {$penaltyFmt} akan dipotong dari refund."
            : "Posisi waiting list Anda untuk Kamar {$roomLabel} telah dibatalkan. Tidak ada denda karena DP belum dibayar.";

        InAppNotification::send(
            $wl->user_id, 'waiting_list_cancelled',
            ' Waiting List Dibatalkan' . ($hasPenalty ? ' — Ada Denda Pembatalan' : ''),
            $userBody,
            route('prospective.waiting-list.index'),
            ['waiting_list_id' => $wl->id]
        );

        $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->where('is_active', true)->get();
        foreach ($admins as $admin) {
            InAppNotification::send(
                $admin->id, 'waiting_list_cancelled_admin',
                " WL Dibatalkan — Kamar {$roomNumber} — {$userName}",
                "{$userName} membatalkan waiting list Kamar {$roomLabel}. Alasan: {$reason}. " .
                    ($hasPenalty ? "Denda 10% = {$penaltyFmt}." : "Tidak ada denda."),
                route('admin.waiting-list.show', $wl->id),
                ['waiting_list_id' => $wl->id]
            );
        }

        if ($wl->user->phone) {
            try {
                $this->sendWa($wl->user->phone,
                    "Halo *{$userName}*,\n\n *Waiting List Dibatalkan*\n\n" .
                    "━━━━━━━━━━━━━━━━\n" .
                    " Kamar   : *{$roomLabel}*\n" .
                    " Alasan  : {$reason}\n" .
                    ($hasPenalty
                        ? " Denda   : *{$penaltyFmt}* (10% dari DP yang dibayar)\n"
                        : " Denda   : Tidak ada (DP belum dibayar)\n"
                    ) .
                    " Waktu   : " . now()->format('d M Y') . " \n" .
                    "━━━━━━━━━━━━━━━━\n\n" .
                    ($hasPenalty
                        ? "Denda akan dipotong dari refund DP Anda. Hubungi admin untuk proses refund."
                        : "Terima kasih, tidak ada denda yang dikenakan."
                    )
                );
            } catch (\Throwable $e) {
                Log::warning('[WA] notifyWaitingListCancelled user: ' . $e->getMessage());
            }
        }

        foreach ($admins as $admin) {
            if ($admin->phone) {
                try {
                    $this->sendWa($admin->phone,
                        " *Waiting List Dibatalkan*\n\n" .
                        "• Calon Penghuni : *{$userName}*\n" .
                        "• Kamar          : *{$roomLabel}*\n" .
                        "• Alasan         : {$reason}\n" .
                        ($hasPenalty
                            ? "• Denda          : *{$penaltyFmt}* (10% dari DP)\n"
                            : "• Denda          : Tidak ada\n"
                        ) .
                        "• Waktu          : " . now()->format('d M Y') . "\n\n" .
                        ($hasPenalty ? "Proses refund DP dikurangi denda." : "Tidak ada refund yang perlu diproses.")
                    );
                } catch (\Throwable $e) {
                    Log::warning('[WA] notifyWaitingListCancelled admin: ' . $e->getMessage());
                }
            }
        }
    }

    public function notifyInvoiceCreated(Invoice $invoice): void
    {
        if (!$invoice->user) return;
        if ($invoice->invoice_type === 'manual') return;
        InAppNotification::send(
            $invoice->user_id, 'invoice_created', ' Tagihan Baru: ' . $invoice->invoice_number,
            'Tagihan sewa senilai Rp ' . number_format($invoice->total_amount, 0, ',', '.') .
                ' jatuh tempo ' . $invoice->due_date?->format('d M Y') . '.',
            $this->invoiceRoute($invoice), ['invoice_id' => $invoice->id]
        );
        if ($invoice->user->phone) {
            try {
                $this->wa->sendPaymentReminder($invoice->user->phone, [
                    'name' => $invoice->user->name, 'invoice_number' => $invoice->invoice_number,
                    'amount' => 'Rp ' . number_format($invoice->total_amount, 0, ',', '.'),
                    'due_date' => $invoice->due_date?->format('d M Y'),
                    'room' => $invoice->room?->room_number, 'invoice_id' => $invoice->id,
                ], 'H-3');
            } catch (\Throwable $e) { Log::warning('[WA] notifyInvoiceCreated: ' . $e->getMessage()); }
        }
    }

    public function notifyInvoicePaid(Invoice $invoice): void
    {
        if (!$invoice->user) return;
        InAppNotification::send(
            $invoice->user_id, 'invoice_paid', ' Pembayaran Dikonfirmasi — Lunas!',
            'Invoice ' . $invoice->invoice_number . ' senilai Rp ' .
                number_format($invoice->total_amount, 0, ',', '.') . ' telah lunas.',
            $this->invoiceRoute($invoice), ['invoice_id' => $invoice->id]
        );
        if ($invoice->user->phone) {
            try {
                $this->wa->sendPaymentConfirmed($invoice->user->phone, [
                    'name' => $invoice->user->name, 'invoice_number' => $invoice->invoice_number,
                    'amount' => 'Rp ' . number_format($invoice->total_amount, 0, ',', '.'),
                    'period' => $invoice->period_start?->format('F Y'),
                    'room' => $invoice->room?->room_number,
                    'verified_at' => now()->format('d M Y') . '',
                ]);
            } catch (\Throwable $e) { Log::warning('[WA] notifyInvoicePaid: ' . $e->getMessage()); }
        }
    }

    public function notifyPaymentReminder(Invoice $invoice, string $stage): void
    {
        if (!$invoice->user) return;
        $titleMap = [
            'H-3' => ' Tagihan Jatuh Tempo 3 Hari Lagi',
            'H-2' => ' Tagihan Jatuh Tempo 2 Hari Lagi',
            'H-1' => ' Tagihan Jatuh Tempo Besok!',
            'H-0' => ' Tagihan Jatuh Tempo HARI INI!',
        ];
        InAppNotification::send(
            $invoice->user_id, 'payment_reminder_' . strtolower(str_replace('-', '', $stage)),
            $titleMap[$stage] ?? ' Pengingat Tagihan',
            'Invoice ' . $invoice->invoice_number . ' senilai Rp ' .
                number_format($invoice->total_amount, 0, ',', '.') .
                ' jatuh tempo ' . $invoice->due_date?->format('d M Y') . '.',
            $this->invoiceRoute($invoice), ['invoice_id' => $invoice->id, 'stage' => $stage]
        );
        if ($invoice->user->phone) {
            try {
                $this->wa->sendPaymentReminder($invoice->user->phone, [
                    'name' => $invoice->user->name, 'invoice_number' => $invoice->invoice_number,
                    'amount' => 'Rp ' . number_format($invoice->total_amount, 0, ',', '.'),
                    'due_date' => $invoice->due_date?->format('d M Y'),
                    'room' => $invoice->room?->room_number, 'invoice_id' => $invoice->id,
                ], $stage);
            } catch (\Throwable $e) { Log::warning('[WA] notifyPaymentReminder: ' . $e->getMessage()); }
        }
    }

    public function notifyPaymentUploaded(\App\Models\Payment $payment): void
    {
        $invoice    = $payment->invoice;
        $userName   = $payment->user?->name ?? $invoice->user?->name ?? 'Calon penghuni';
        $roomNumber = $invoice->room?->room_number ?? '-';
        $amount     = 'Rp ' . number_format($payment->amount, 0, ',', '.');
        $isDP        = str_contains($invoice->invoice_number ?? '', '-DP-');
        $isRemaining = str_contains($invoice->invoice_number ?? '', '-SISA-');
        $jenisLabel  = $isDP ? 'DP Waiting List' : ($isRemaining ? 'Pelunasan Waiting List' : 'Pembayaran');

        $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->where('is_active', true)->get();
        foreach ($admins as $admin) {
            InAppNotification::send(
                $admin->id, 'payment_uploaded', " Bukti {$jenisLabel} Masuk — Kamar {$roomNumber}",
                "{$userName} mengupload bukti {$jenisLabel} invoice {$invoice->invoice_number} senilai {$amount}. Segera verifikasi.",
                route('admin.invoices.show', $invoice->id), ['invoice_id' => $invoice->id, 'payment_id' => $payment->id]
            );
            if ($admin->phone) {
                try {
                    $this->sendWa($admin->phone,
                        " *Bukti {$jenisLabel} Masuk*\n\n• Nama    : *{$userName}*\n• Kamar   : *{$roomNumber}*\n" .
                        "• Invoice : {$invoice->invoice_number}\n• Nominal : *{$amount}*\n" .
                        "• Metode  : " . ucfirst(str_replace('_', ' ', $payment->method)) . "\n" .
                        "• Waktu   : " . now()->format('d M Y') . "\n\nSegera verifikasi di dashboard admin."
                    );
                } catch (\Throwable $e) { Log::warning('[WA] notifyPaymentUploaded admin: ' . $e->getMessage()); }
            }
        }
    }

    public function notifyAdminInvoicePaidNeedsActivation(Invoice $invoice): void
    {
        $admins     = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->where('is_active', true)->get();
        $userName   = $invoice->user?->name ?? 'Calon penghuni';
        $roomNumber = $invoice->room?->room_number ?? '-';
        $amount     = 'Rp ' . number_format($invoice->total_amount, 0, ',', '.');
        foreach ($admins as $admin) {
            InAppNotification::send(
                $admin->id, 'invoice_paid_needs_activation', ' Tagihan Lunas — Perlu Aktivasi Penghuni',
                "{$userName} membayar invoice {$invoice->invoice_number} ({$amount}). Aktifkan sebagai penghuni kamar {$roomNumber}.",
                route('admin.invoices.show', $invoice->id), ['invoice_id' => $invoice->id]
            );
        }
    }

    public function notifyPaymentVerified(\App\Models\Payment $payment): void
    {
        $invoice = $payment->invoice;
        if (!$invoice) return;
        InAppNotification::send(
            $payment->user_id, 'payment_verified', ' Pembayaran Terverifikasi',
            'Pembayaran invoice ' . ($invoice->invoice_number ?? '-') .
                ' sebesar Rp ' . number_format($payment->amount, 0, ',', '.') . ' telah dikonfirmasi.',
            $this->invoiceRoute($invoice), ['payment_id' => $payment->id, 'invoice_id' => $invoice->id]
        );
    }

    public function notifyNewTicket(\App\Models\Ticket $ticket): void
    {
        $priorityLabel = ['low' => ' Rendah', 'normal' => ' Normal', 'high' => ' Tinggi', 'urgent' => ' Urgent'][$ticket->priority] ?? strtoupper($ticket->priority);
        $isUrgent = $ticket->priority === 'urgent';
        $admins   = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->where('is_active', true)->get();

        foreach ($admins as $admin) {
            InAppNotification::send(
                $admin->id, 'new_ticket', $isUrgent ? ' Tiket URGENT Masuk!' : ' Tiket Baru Masuk',
                ($ticket->user?->name ?? 'Penghuni') . ' membuat tiket: "' . $ticket->subject . '" [' . $priorityLabel . ']',
                route('admin.tickets.show', $ticket->id), ['ticket_id' => $ticket->id, 'priority' => $ticket->priority]
            );
            if ($admin->phone) {
                try {
                    $this->wa->sendNewTicketAdmin($admin->phone, [
                        'admin_name' => $admin->name, 'name' => $ticket->user?->name ?? '-',
                        'room' => $ticket->user?->activeRoom?->room_number ?? '-',
                        'ticket_number' => $ticket->ticket_number, 'category' => $ticket->category,
                        'priority' => $ticket->priority, 'subject' => $ticket->subject, 'ticket_id' => $ticket->id,
                    ]);
                } catch (\Throwable $e) { Log::warning('[WA] notifyNewTicket: ' . $e->getMessage()); }
            }
        }
    }

   public function notifyTicketRepliedByAdmin(\App\Models\Ticket $ticket, string $message): void
    {
        InAppNotification::send(
            $ticket->user_id, 'ticket_replied', ' Admin Membalas Tiket Anda',
            'Tiket "' . $ticket->subject . '" mendapat balasan dari admin. Cek sekarang!',
            route('tenant.tickets.show', $ticket->id), ['ticket_id' => $ticket->id]
        );

        $user = $ticket->user ?? \App\Models\User::find($ticket->user_id);
        if ($user?->phone) {
            try {
                $this->sendWa($user->phone,
                    "Halo *{$user->name}*,\n\n *Admin Membalas Tiket Anda*\n\n" .
                    "━━━━━━━━━━━━━━━━\n" .
                    " No. Tiket : *{$ticket->ticket_number}*\n" .
                    " Subjek    : {$ticket->subject}\n" .
                    " Balasan   : {$message}\n" .
                    " Waktu     : " . now()->format('d M Y') ."\n" .
                    "━━━━━━━━━━━━━━━━\n\n" .
                    "Silakan buka aplikasi untuk membalas atau menutup tiket."
                );
            } catch (\Throwable $e) {
                Log::warning('[WA] notifyTicketRepliedByAdmin user: ' . $e->getMessage());
            }
        }
    }

    public function notifyTicketRepliedByTenant(\App\Models\Ticket $ticket, string $message): void
    {
        $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->where('is_active', true)->get();
        foreach ($admins as $admin) {
            InAppNotification::send(
                $admin->id, 'ticket_reply_tenant', ' Penghuni Membalas Tiket',
                ($ticket->user?->name ?? 'Penghuni') . ' membalas tiket "' . $ticket->subject . '" [' . $ticket->ticket_number . ']',
                route('admin.tickets.show', $ticket->id), ['ticket_id' => $ticket->id]
            );

            if ($admin->phone) {
                try {
                    $this->sendWa($admin->phone,
                        " *Penghuni Membalas Tiket*\n\n" .
                        "• Penghuni  : *{$ticket->user?->name}*\n" .
                        "• No. Tiket : *{$ticket->ticket_number}*\n" .
                        "• Subjek    : {$ticket->subject}\n" .
                        "• Balasan   : {$message}\n" .
                        "• Waktu     : " . now()->format('d M Y') . "\n\n" .
                        "Silakan buka dashboard admin untuk membalas."
                    );
                } catch (\Throwable $e) {
                    Log::warning('[WA] notifyTicketRepliedByTenant admin: ' . $e->getMessage());
                }
            }
        }
    }

    public function notifyTicketClosedByTenant(\App\Models\Ticket $ticket): void
    {
        $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->where('is_active', true)->get();
        foreach ($admins as $admin) {
            InAppNotification::send(
                $admin->id, 'ticket_closed', ' Tiket Ditutup oleh Penghuni',
                ($ticket->user?->name ?? 'Penghuni') . ' menutup tiket "' . $ticket->subject . '" [' . $ticket->ticket_number . '] — masalah selesai.',
                route('admin.tickets.show', $ticket->id), ['ticket_id' => $ticket->id]
            );

            if ($admin->phone) {
                try {
                    $this->sendWa($admin->phone,
                        " *Tiket Ditutup oleh Penghuni*\n\n" .
                        "• Penghuni  : *{$ticket->user?->name}*\n" .
                        "• No. Tiket : *{$ticket->ticket_number}*\n" .
                        "• Subjek    : {$ticket->subject}\n" .
                        "• Kategori  : " . ucfirst($ticket->category) . "\n" .
                        "• Waktu     : " . now()->format('d M Y') . "\n\n" .
                        "Penghuni menyatakan masalah sudah selesai."
                    );
                } catch (\Throwable $e) {
                    Log::warning('[WA] notifyTicketClosedByTenant admin: ' . $e->getMessage());
                }
            }
        }
    }

    public function notifyTicketResolved(\App\Models\Ticket $ticket): void
    {
        InAppNotification::send(
            $ticket->user_id, 'ticket_resolved', ' Tiket Diselesaikan',
            'Tiket "' . $ticket->subject . '" (' . $ticket->ticket_number . ') telah diselesaikan oleh admin. Berikan penilaian Anda!',
            route('tenant.tickets.show', $ticket->id), ['ticket_id' => $ticket->id]
        );

        $user = $ticket->user ?? \App\Models\User::find($ticket->user_id);
        if ($user?->phone) {
            try {
                $this->sendWa($user->phone,
                    "Halo *{$user->name}*,\n\n *Tiket Anda Telah Diselesaikan*\n\n" .
                    "━━━━━━━━━━━━━━━━\n" .
                    " No. Tiket : *{$ticket->ticket_number}*\n" .
                    " Subjek    : {$ticket->subject}\n" .
                    " Waktu     : " . now()->format('d M Y') . "\n" .
                    "━━━━━━━━━━━━━━━━\n\n" .
                    "Silakan buka aplikasi untuk memberikan penilaian. Terima kasih! "
                );
            } catch (\Throwable $e) {
                Log::warning('[WA] notifyTicketResolved user: ' . $e->getMessage());
            }
        }
    }

    public function notifyTenantUpgraded(User $user, ?Room $room = null): void
    {
        InAppNotification::send(
            $user->id, 'role_upgraded', ' Selamat! Anda Kini Penghuni Aktif',
            'Akun Anda telah diupgrade menjadi Penghuni Aktif Urban Kost.' . ($room ? ' Kamar: ' . $room->room_number . '.' : ''),
            route('tenant.dashboard'), ['room_id' => $room?->id]
        );
        if ($user->phone) {
            try {
                $this->wa->sendWelcomeTenant($user->phone, [
                    'name' => $user->name, 'room' => $room?->room_number ?? '-', 'email' => $user->email,
                ]);
            } catch (\Throwable $e) { Log::warning('[WA] notifyTenantUpgraded: ' . $e->getMessage()); }
        }
    }

    public function notifyTenancyEnded(User $user, ?Room $room = null): void
    {
        $roomNumber = $room?->room_number ?? '-';
        InAppNotification::send(
            $user->id, 'tenancy_ended', ' Masa Sewa Anda Telah Berakhir',
            "Masa sewa Anda untuk Kamar {$roomNumber} telah berakhir. Terima kasih telah menjadi penghuni Urban Kost.",
            route('home'), ['room_id' => $room?->id]
        );
        if ($user->phone) {
            try {
                $this->sendWa($user->phone,
                    "Halo *{$user->name}*,\n\nMasa sewa Anda untuk *Kamar {$roomNumber}* telah berakhir.\n\n" .
                    "Terima kasih telah menjadi penghuni Urban Kost. Semoga dapat bertemu lagi! "
                );
            } catch (\Throwable $e) { Log::warning('[WA] notifyTenancyEnded: ' . $e->getMessage()); }
        }
    }

    public function notifyOldTenantAdvanceBooked(?int $oldTenantUserId, $room): void
    {
        if (!$oldTenantUserId) return;
        $user = User::find($oldTenantUserId);
        if (!$user) return;
        $roomNumber = $room?->room_number ?? '-';
        InAppNotification::send(
            $user->id, 'advance_booking_notice', ' Ada Calon Penghuni Baru untuk Kamar Anda',
            "Ada calon penghuni yang mendaftar untuk Kamar {$roomNumber} setelah masa sewa Anda berakhir. Admin akan menghubungi Anda untuk konfirmasi perpanjangan.",
            $this->safeRoute('tenant.renewal.index'), ['room_id' => $room?->id]
        );
    }

    public function notifyOldTenantRoomHandover(?int $oldTenantUserId, $room): void
    {
        if (!$oldTenantUserId) return;
        $user = User::find($oldTenantUserId);
        if (!$user) return;
        $roomNumber = $room?->room_number ?? '-';
        InAppNotification::send(
            $user->id, 'room_handover_notice', ' Kamar Akan Diserahterimakan',
            "Kamar {$roomNumber} Anda akan segera ditempati penghuni baru. Mohon pastikan kamar sudah dibersihkan dan kunci diserahkan ke admin.",
            $this->safeRoute('tenant.dashboard'), ['room_id' => $room?->id]
        );
    }

    public function notifyTenancyExpiringSoon(User $user, Booking $booking, int $daysLeft): void
    {
        $room       = $booking->room;
        $roomNumber = $room?->room_number ?? '-';
        $endDate    = $booking->end_date
            ? \Carbon\Carbon::parse($booking->end_date)->format('d M Y')
            : '-';

        $emoji   = $daysLeft <= 3 ? '' : '';
        $urgency = $daysLeft <= 3 ? 'SEGERA' : 'segera';

        InAppNotification::send(
            $user->id,
            'tenancy_expiring_soon',
            "{$emoji} Masa Sewa Hampir Berakhir — {$daysLeft} Hari Lagi!",
            "Masa sewa Kamar {$roomNumber} Anda akan berakhir pada {$endDate} ({$daysLeft} hari lagi). " .
                "Silakan {$urgency} hubungi admin atau ajukan perpanjangan sewa.",
            $this->safeRoute('tenant.renewal.index'),
            [
                'booking_id' => $booking->id,
                'room_id'    => $room?->id,
                'days_left'  => $daysLeft,
                'end_date'   => $booking->end_date,
            ]
        );

        if ($user->phone) {
            try {
                $this->sendWa($user->phone,
                    "Halo *{$user->name}*,\n\n" .
                    "{$emoji} *Masa Sewa Hampir Berakhir!*\n\n" .
                    "━━━━━━━━━━━━━━━━\n" .
                    " Kamar        : *{$roomNumber}*\n" .
                    " Berakhir     : *{$endDate}*\n" .
                    " Sisa Waktu   : *{$daysLeft} hari lagi*\n" .
                    "━━━━━━━━━━━━━━━━\n\n" .
                    "Jika ingin memperpanjang sewa, silakan hubungi admin atau ajukan perpanjangan melalui aplikasi.\n\n" .
                    "Terima kasih telah menjadi penghuni Urban Kost "
                );
            } catch (\Throwable $e) {
                Log::warning('[WA] notifyTenancyExpiringSoon: ' . $e->getMessage());
            }
        }

        Log::info('[NotifService] notifyTenancyExpiringSoon sent', [
            'user_id'    => $user->id,
            'booking_id' => $booking->id,
            'days_left'  => $daysLeft,
        ]);
    }

    private function invoiceRoute(Invoice $invoice): string
    {
        if (!$invoice->user) return route('home');
        $role = $invoice->user->role?->name;
        if ($role === 'tenant') return route('tenant.invoices.show', $invoice->id);
        if ($this->routeExists('prospective.invoices.show')) return route('prospective.invoices.show', $invoice->id);
        return route('prospective.dashboard');
    }

    private function safeRoute(string $name, $params = []): string
    {
        try { return route($name, $params); }
        catch (\Throwable) { return route('home'); }
    }

    private function routeExists(string $name): bool
    {
        try { route($name, [0]); return true; }
        catch (\Throwable) { return false; }
    }

    private function sendWa(string $phone, string $message): void
    {
        if (method_exists($this->wa, 'sendText')) {
            $this->wa->sendText($phone, $message);
        } elseif (method_exists($this->wa, 'sendMessage')) {
            $this->wa->sendMessage($phone, $message);
        } elseif (method_exists($this->wa, 'send')) {
            $this->wa->send($phone, $message);
        }
    }
}

if (!function_exists('route_exists')) {
    function route_exists(string $name): bool
    {
        try { route($name, [0]); return true; }
        catch (\Throwable) { return false; }
    }
}
