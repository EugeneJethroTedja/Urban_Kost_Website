<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\InAppNotification;
use App\Models\Invoice;
use App\Models\Role;
use App\Models\User;
use App\Models\WaitingList;
use App\Models\Room;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WaitingListController extends Controller
{
    public function index(Request $request)
    {
        $query = WaitingList::with(['user', 'room', 'booking', 'approvedBy'])
            ->orderByRaw("FIELD(status, 'waiting', 'notified', 'completed', 'cancelled', 'expired')")
            ->orderBy('approved_at', 'asc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        $waitingLists = $query->paginate(15)->withQueryString();
        $rooms = Room::orderBy('room_number')->get(['id', 'room_number', 'room_name']);

        return view('admin.waiting-list.index', compact('waitingLists', 'rooms'));
    }

    public function show($id)
    {
        $wl = WaitingList::with([
            'user', 'room', 'booking', 'approvedBy',
            'dpInvoice.payments', 'remainingInvoice.payments',
        ])->findOrFail($id);

        $dpInvoice = $this->lookupDpInvoice($wl);
        $dpIsPaid  = $dpInvoice && $dpInvoice->status === 'paid';

        $remainingInvoice = Invoice::where('waiting_list_id', $wl->id)
            ->where('invoice_type', 'waiting_list_remaining')
            ->latest()
            ->first();

        $oldTenantLeasePeriod = null;
        $readiness = $this->resolveNotifyReadiness($wl);
        if ($readiness) {
            $oldTenantLeasePeriod = [
                'is_expired'  => $readiness['is_ready'],
                'end_date'    => $readiness['period_start'],
                'tenant_name' => $wl->user?->name,
            ];
        }

        return view('admin.waiting-list.show', compact(
            'wl', 'dpInvoice', 'dpIsPaid', 'remainingInvoice', 'oldTenantLeasePeriod'
        ));
    }

    public function createDpInvoice(WaitingList $wl): Invoice
    {
        if ($wl->dp_invoice_id) {
            $existing = Invoice::find($wl->dp_invoice_id);
            if ($existing) return $existing;
        }

        $existing = $this->lookupDpInvoice($wl);
        if ($existing) return $existing;

        $pricePerMonth   = $wl->room?->price ?? 0;
        $duration        = $wl->booking?->duration_months ?? 1;
        $servicePerMonth = (float) ($wl->booking?->service_amount ?? 0);

        $totalSewa  = $pricePerMonth * $duration;
        $svcTotal   = $servicePerMonth * $duration;
        $grandTotal = $totalSewa + $svcTotal;

        if (!empty($wl->dp_amount) && $wl->dp_amount > 0) {
            $dpAmount = (int) $wl->dp_amount;
        } else {
            $paymentMethod = $wl->booking?->payment_method ?? 'lump_sum';
            if ($paymentMethod === 'monthly') {
                $dpAmount = (int) round(($pricePerMonth + $servicePerMonth) * 0.50);
            } else {
                $dpAmount = (int) round($grandTotal * 0.50);
            }
        }

        $checkInDate = $wl->booking?->check_in_date;
        $periodStart = $checkInDate?->toDateString() ?? now()->toDateString();
        $periodEnd   = $checkInDate
            ? $checkInDate->copy()->addMonths($duration)->toDateString()
            : now()->addMonths($duration)->toDateString();

        $invoice = Invoice::create([
            'user_id'         => $wl->user_id,
            'room_id'         => $wl->room_id,
            'booking_id'      => $wl->booking_id,
            'waiting_list_id' => $wl->id,
            'invoice_number'  => 'INV-DP-' . date('Ymd') . '-' . str_pad($wl->id, 4, '0', STR_PAD_LEFT),
            'invoice_type' => 'waiting_list_dp',
            'base_amount'     => $dpAmount,
            'service_amount'  => 0,
            'total_amount'    => $dpAmount,
            'tax_amount'      => 0,
            'status'          => 'unpaid',
            'due_date' => now()->addDays(7),
            'period_start'    => $periodStart,
            'period_end'      => $periodEnd,
            'notes'           => 'DP 50% Waiting List Kamar ' . $wl->room?->room_number .
                                 ' (' . $duration . ' bln). Grand total Rp ' . number_format($grandTotal, 0, ',', '.') .
                                 '. Rencana masuk: ' . $periodStart . '.',
        ]);

        $wl->update([
            'dp_amount'     => $dpAmount,
            'dp_invoice_id' => $invoice->id,
        ]);

        return $invoice;
    }

    public function notifyAvailable(Request $request, $id)
    {
        $wl = WaitingList::with(['user', 'room', 'booking'])->findOrFail($id);

        if (!in_array($wl->status, ['waiting', 'notified'])) {
            return back()->with('error', 'Status waiting list tidak valid (saat ini: ' . $wl->status . ').');
        }

        $readiness = $this->resolveNotifyReadiness($wl);
        if ($readiness && !$readiness['is_ready']) {
            $tgl = $readiness['period_start']->format('d M Y');
            return back()->with('error',
                ' Belum bisa mengirim notifikasi. Tanggal check-in ' .
                '<strong>' . ($wl->user?->name ?? '-') . '</strong> ' .
                'baru dimulai pada <strong>' . $tgl . '</strong>. ' .
                'Kirim notifikasi pada atau setelah tanggal tersebut.'
            );
        }

        $dpInvoice = Invoice::where('waiting_list_id', $wl->id)
            ->where('invoice_number', 'like', 'INV-DP-%')
            ->where('status', 'paid')
            ->latest()
            ->first();

        if (!$dpInvoice) {
            $dpInvoice = Invoice::where('user_id', $wl->user_id)
                ->where('room_id', $wl->room_id)
                ->where('invoice_number', 'like', 'INV-DP-%')
                ->where('status', 'paid')
                ->latest()
                ->first();
        }

        if (!$dpInvoice) {
            return back()->with('error', 'DP belum lunas. Calon penghuni harus melunasi DP terlebih dahulu.');
        }

        $sisaInvoiceExists = Invoice::where('waiting_list_id', $wl->id)
            ->where('invoice_number', 'like', 'INV-SISA-%')
            ->exists();

        if ($sisaInvoiceExists) {
            $existingSisa = Invoice::where('waiting_list_id', $wl->id)
                ->where('invoice_number', 'like', 'INV-SISA-%')
                ->latest()
                ->first();

            $wl->update(['notified_at' => now()]);

            (new NotificationService())->notifyRoomAvailableForWaitingList(
                $wl->fresh(),
                (int) ($existingSisa?->total_amount ?? 0)
            );

            return back()->with('success',
                ' Notifikasi ulang berhasil dikirim ke <strong>' . $wl->user?->name . '</strong>. ' .
                'Invoice pelunasan yang sudah ada tetap digunakan.'
            );
        }

        $paymentMethod   = $wl->booking?->payment_method ?? 'lump_sum';
        $pricePerMonth   = $wl->room?->price ?? 0;
        $duration        = $wl->booking?->duration_months ?? 1;
        $servicePerMonth = (float) ($wl->booking?->service_amount ?? 0);
        $grandTotal      = ($pricePerMonth + $servicePerMonth) * $duration;
        $dpPaid          = (int) $dpInvoice->total_amount;

        $checkIn    = $wl->booking?->check_in_date ?? now();
        $billingDay = min((int) \Carbon\Carbon::parse($checkIn)->format('d'), 28);

        if ($paymentMethod === 'monthly') {
            $bilanBulanPertama = $pricePerMonth + $servicePerMonth;
            $sisaAmount        = max(0, (int) round($bilanBulanPertama * 0.5));

            $notesText = 'Pelunasan 50% sisa biaya bulan pertama kamar ' . $wl->room?->room_number .
                        '. Bulan ke-2 dst ditagih otomatis per bulan saat aktif. ' .
                        'DP terbayar: Rp ' . number_format($dpPaid, 0, ',', '.') .
                        '. Total sewa ' . $duration . ' bln: Rp ' . number_format($grandTotal, 0, ',', '.') . '.';
        } else {
            $sisaAmount = max(0, $grandTotal - $dpPaid);

            $notesText = 'Pelunasan sewa kamar ' . $wl->room?->room_number .
                        '. Grand total Rp ' . number_format($grandTotal, 0, ',', '.') .
                        ' (' . $duration . ' bln), DP Rp ' . number_format($dpPaid, 0, ',', '.') .
                        ', Sisa Rp ' . number_format($sisaAmount, 0, ',', '.') . '.';
        }

        $checkInDate = $wl->booking?->check_in_date;
        $periodStart = $checkInDate?->toDateString() ?? now()->toDateString();

        $periodEnd = $paymentMethod === 'monthly'
            ? ($checkInDate
                ? $checkInDate->copy()->addMonth()->subDay()->toDateString()
                : now()->addMonth()->subDay()->toDateString())
            : ($checkInDate
                ? $checkInDate->copy()->addMonths($duration)->toDateString()
                : now()->addMonths($duration)->toDateString());

        DB::transaction(function () use (
            $wl, $sisaAmount, $periodStart, $periodEnd, $notesText,
            $paymentMethod, $billingDay  // ← dua variabel baru masuk closure
        ) {
            if ($sisaAmount > 0) {
                Invoice::create([
                    'user_id'         => $wl->user_id,
                    'room_id'         => $wl->room_id,
                    'booking_id'      => $wl->booking_id,
                    'waiting_list_id' => $wl->id,
                    'invoice_number'  => 'INV-SISA-' . date('Ymd') . '-' . str_pad($wl->id, 4, '0', STR_PAD_LEFT),
                    'invoice_type' => 'waiting_list_remaining',
                    'invoice_type'    => 'waiting_list_remaining',
                    'base_amount'     => $sisaAmount,
                    'service_amount'  => 0,
                    'total_amount'    => $sisaAmount,
                    'tax_amount'      => 0,
                    'status'          => 'unpaid',
                    'due_date'        => now()->toDateString(),
                    'period_start'    => $periodStart,
                    'period_end'      => $periodEnd,
                    'notes'           => $notesText . ' Periode: ' . $periodStart . ' s/d ' . $periodEnd . '.',
                ]);
            }

            $wl->update(['status' => 'notified', 'notified_at' => now()]);
            $wl->room?->update(['status' => 'available']);

            if ($paymentMethod === 'monthly' && $wl->booking_id) {
                \App\Models\Booking::where('id', $wl->booking_id)
                    ->update([
                        'monthly_billing_day'   => $billingDay,
                        'monthly_invoice_count' => 1,
                    ]);

                Log::info(
                    '[WaitingListController] notifyAvailable: set monthly fields booking #' . $wl->booking_id .
                    ' billing_day=' . $billingDay . ' invoice_count=1'
                );
            }
        });

        (new NotificationService())->notifyRoomAvailableForWaitingList($wl->fresh(), $sisaAmount);

        $sisaLabel = $paymentMethod === 'monthly'
            ? 'Sisa 50% bulan pertama'
            : 'Invoice pelunasan';

        return back()->with('success',
            ' Notifikasi dikirim ke <strong>' . $wl->user?->name . '</strong>.' .
            ($sisaAmount > 0
                ? ' ' . $sisaLabel . ' <strong>Rp ' . number_format($sisaAmount, 0, ',', '.') . '</strong> diterbitkan.'
                : ' DP sudah lunas penuh.')
        );
    }

    public function activateTenant(Request $request, $id)
    {
        $wl = WaitingList::with([
            'user', 'room', 'booking',
        ])->findOrFail($id);
 
        if (!in_array($wl->status, ['waiting', 'notified'])) {
            return back()->with('error',
                'Hanya waiting list berstatus "waiting" atau "notified" yang dapat diaktifkan (saat ini: ' . $wl->status . ').'
            );
        }
 
        $remainingInvoice = Invoice::where('waiting_list_id', $wl->id)
            ->where('invoice_type', 'waiting_list_remaining')
            ->latest()
            ->first();
 
        if ($remainingInvoice && $remainingInvoice->status !== 'paid') {
            return back()->with('error',
                'Invoice pelunasan (' . $remainingInvoice->invoice_number . ') belum lunas. Selesaikan pembayaran terlebih dahulu.'
            );
        }
 
        if (!$remainingInvoice) {
            $dpInvoice = $this->lookupDpInvoice($wl, true);
            if (!$dpInvoice) {
                return back()->with('error', 'DP belum lunas. Tidak dapat mengaktifkan penghuni.');
            }
        }
 
        $user            = $wl->user;
        $roomId          = $wl->room_id;
        $notif           = new NotificationService();
        $upgraded        = false;
        $oldTenantName   = null;
        $oldTenantUserId = $wl->booking?->advance_for_user_id;
        $paymentMethod   = $wl->booking?->payment_method ?? 'lump_sum'; 
 
        DB::transaction(function () use ($wl, $user, $roomId, $oldTenantUserId, $paymentMethod, &$upgraded, &$oldTenantName, $notif) {
 
            $oldBookings = Booking::where('room_id', $roomId)
                ->whereIn('status', ['approved', 'active'])
                ->where('user_id', '!=', $user->id)
                ->with('user.role')
                ->get();
 
            foreach ($oldBookings as $oldBooking) {
                $oldTenantUser = $oldBooking->user;
 
                $oldBooking->update([
                    'status'   => 'completed',
                    'end_date' => now()->toDateString(),
                ]);
 
                Log::info('[WaitingListController] Booking lama #' . $oldBooking->id .
                    ' (' . ($oldTenantUser?->name ?? '-') . ') → completed.');
 
                if ($oldTenantUser) {
                    $stillHasRoom = Booking::where('user_id', $oldTenantUser->id)
                        ->whereIn('status', ['approved', 'active'])
                        ->where('id', '!=', $oldBooking->id)
                        ->exists();
 
                    if (!$stillHasRoom && $oldTenantUser->role?->name === 'tenant') {
                        $prospectiveRole = Role::where('name', 'prospective_tenant')->first();
                        if ($prospectiveRole) {
                            $oldTenantUser->update(['role_id' => $prospectiveRole->id]);
                            Log::info('[WaitingListController] User lama #' . $oldTenantUser->id . ' → prospective_tenant.');
                        }
 
                        InAppNotification::send(
                            $oldTenantUser->id,
                            'tenancy_ended',
                            ' Masa Sewa Kamar Telah Berakhir',
                            'Masa sewa kamar ' . ($wl->room?->room_number ?? '-') .
                                ' Anda telah resmi berakhir. Terima kasih telah menjadi penghuni kami.',
                            route('home')
                        );
 
                        try {
                            $notif->notifyTenancyEnded($oldTenantUser, $wl->room);
                        } catch (\Exception $e) {
                            Log::warning('[WaitingListController] notifyTenancyEnded gagal: ' . $e->getMessage());
                        }
 
                        $oldTenantName = $oldTenantUser->name;
                    }
                }
            }
 
            if ($oldTenantUserId && !$oldBookings->firstWhere('user_id', $oldTenantUserId)) {
                $directOldTenant = User::with('role')->find($oldTenantUserId);
                if ($directOldTenant && $directOldTenant->role?->name === 'tenant') {
                    $stillHasRoom = Booking::where('user_id', $oldTenantUserId)
                        ->whereIn('status', ['approved', 'active'])
                        ->exists();
 
                    if (!$stillHasRoom) {
                        $prospectiveRole = Role::where('name', 'prospective_tenant')->first();
                        if ($prospectiveRole) {
                            $directOldTenant->update(['role_id' => $prospectiveRole->id]);
                        }
                        $oldTenantName = $oldTenantName ?? $directOldTenant->name;
                    }
                }
            }
 
            if ($user) {
                $tenantRole = Role::where('name', 'tenant')->firstOrFail();
                $user->update(['role_id' => $tenantRole->id, 'is_active' => true]);
                LoginController::generateAndSendOtp($user->email, 'login', $user->name);
                try {
                    $notif->notifyTenantUpgraded($user, $wl->room);
                } catch (\Exception $e) {
                    Log::warning('[WaitingListController] notifyTenantUpgraded gagal: ' . $e->getMessage());
                }
                $upgraded = true;
                Log::info('[WaitingListController] User #' . $user->id . ' (' . $user->name . ') → tenant.');
            }
 
            if ($wl->booking) {
                $bookingUpdate = [
                    'status'  => 'active',
                    'room_id' => $roomId,
                ];
 
                if (empty($wl->booking->check_in_date)) {
                    $bookingUpdate['check_in_date'] = now()->toDateString();
                }
 
                if ($paymentMethod === 'monthly') {
                    $checkIn    = $wl->booking->check_in_date ?? now(); 
                    $billingDay = min((int) \Carbon\Carbon::parse($checkIn)->format('d'), 28);
                    $bookingUpdate['monthly_billing_day']   = $billingDay;
                    $bookingUpdate['monthly_invoice_count'] = 1;
                }
                $wl->booking->update($bookingUpdate);
 
                Log::info('[WaitingListController] Booking #' . $wl->booking->id .
                    ' → active. PaymentMethod: ' . $paymentMethod);
            }
 
            WaitingList::where('room_id', $roomId)
                ->whereIn('status', ['waiting', 'notified'])
                ->update([
                    'status'       => 'completed',
                    'converted_at' => now(),
                ]);
 
            $wl->refresh();
            if (!in_array($wl->status, ['completed'])) {
                $wl->update([
                    'status'       => 'completed',
                    'converted_at' => now(),
                ]);
            }
 
            $wl->room?->update(['status' => 'occupied']);
 
            Log::info('[WaitingListController] activateTenant WL #' . $wl->id . ' selesai. Room #' . $roomId . ' → occupied.');
        });
 
        if ($oldTenantUserId) {
            try {
                $notif->notifyOldTenantRoomHandover($oldTenantUserId, $wl->room);
            } catch (\Exception $e) {
                Log::warning('[WaitingListController] notifyOldTenantRoomHandover gagal: ' . $e->getMessage());
            }
 
            InAppNotification::send(
                $oldTenantUserId,
                'room_handover_complete',
                ' Penghuni Baru Sudah Aktif',
                'Kamar ' . $wl->room?->room_number . ' kini resmi ditempati penghuni baru. ' .
                    'Terima kasih sudah menjadi penghuni kami.',
                route('home'),
                ['room_id' => $roomId]
            );
        }
 
        InAppNotification::send(
            $wl->user_id,
            'tenancy_activated',
            ' Selamat! Anda Resmi Menjadi Penghuni',
            'Akun Anda telah diaktifkan sebagai penghuni kamar ' . $wl->room?->room_number .
                '. Hubungi admin untuk serah terima kunci.',
            route('tenant.dashboard'),
            ['room_id' => $roomId]
        );
 
        if ($paymentMethod === 'monthly') {
            $billingDay = $wl->booking?->monthly_billing_day ?? 1;
            InAppNotification::send(
                $wl->user_id,
                'monthly_schedule_info',
                ' Jadwal Tagihan Bulanan Anda',
                'Tagihan bulan ke-2 dan seterusnya akan dikirim otomatis setiap tanggal ' .
                    $billingDay . ' tiap bulan.',
                route('tenant.invoices.index'),
                ['booking_id' => $wl->booking_id]
            );
        }
 
        $successMsg = ' <strong>' . $user?->name . '</strong> berhasil diaktifkan sebagai penghuni ' .
            'kamar <strong>' . $wl->room?->room_number . '</strong>. ' .
            ($upgraded ? 'OTP login dikirim ke ' . $user?->email . '. ' : '') .
            ($oldTenantName ? 'Penghuni lama <strong>' . $oldTenantName . '</strong> telah diselesaikan statusnya.' : '');
 
        if ($paymentMethod === 'monthly') {
            $successMsg .= ' Tagihan bulanan otomatis setiap tanggal <strong>' . ($wl->booking?->monthly_billing_day ?? '?') . '</strong>.';
        }
 
        return back()->with('success', $successMsg);
    }

    public function waivePenalty(Request $request, $id)
    {
        $wl = WaitingList::findOrFail($id);

        if ($wl->status !== 'cancelled') {
            return back()->with('error', 'Denda hanya bisa dibebaskan jika waiting list sudah dibatalkan.');
        }

        $wl->update(['penalty_waived' => true]);

        return back()->with('success',
            'Denda untuk <strong>' . $wl->user?->name . '</strong> telah dibebaskan.'
        );
    }

    private function resolveNotifyReadiness(WaitingList $wl): ?array
    {
        $dpInvoice = Invoice::where('invoice_type', 'waiting_list_dp')
            ->where(function ($q) use ($wl) {
                $q->where('waiting_list_id', $wl->id)
                  ->orWhere('booking_id', $wl->booking_id)
                  ->orWhere(function ($q2) use ($wl) {
                      $q2->where('user_id', $wl->user_id)
                         ->where('room_id', $wl->room_id);
                  });
            })
            ->latest()
            ->first();

        $periodStart = null;
        if ($dpInvoice?->period_start) {
            $periodStart = \Carbon\Carbon::parse($dpInvoice->period_start);
        } elseif ($wl->booking?->check_in_date) {
            $periodStart = \Carbon\Carbon::parse($wl->booking->check_in_date);
        }

        if (!$periodStart) {
            return null;
        }

        return [
            'is_ready'     => now()->gte($periodStart),
            'period_start' => $periodStart,
        ];
    }

    private function lookupDpInvoice(WaitingList $wl, bool $paidOnly = false): ?Invoice
    {
        $query = Invoice::where('invoice_type', 'waiting_list_dp')
            ->where(function ($q) use ($wl) {
                $q->where('waiting_list_id', $wl->id)
                  ->orWhere('booking_id', $wl->booking_id)
                  ->orWhere(function ($q2) use ($wl) {
                      $q2->where('user_id', $wl->user_id)
                         ->where('room_id', $wl->room_id);
                  });
            });

        if ($paidOnly) {
            $query->where('status', 'paid');
        }

        return $query->orderByDesc('paid_at')->latest()->first();
    }
}
