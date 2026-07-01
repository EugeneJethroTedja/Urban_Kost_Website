<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\InAppNotification;
use App\Models\Invoice;
use App\Models\Renewal;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RenewalController extends Controller
{
    public function index(Request $request)
    {
        $query = Renewal::with(['user', 'room', 'invoice'])->latest();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = trim($request->input('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('room', function ($r) use ($search) {
                    $r->where('room_number', 'like', "%{$search}%");
                });
            });
        }

        $allRenewals = Renewal::all();

        $counts = [
            'pending_admin'   => $allRenewals->where('status', 'pending_admin')->count(),
            'pending_payment' => $allRenewals->where('status', 'pending_payment')->count(),
            'approved'        => $allRenewals->where('status', 'approved')->count(),
            'rejected'        => $allRenewals->where('status', 'rejected')->count(),
        ];

        $renewals = $query->paginate(20)->withQueryString();

        return view('admin.renewals.index', compact('renewals', 'counts'));
    }

    public function show($id)
    {
        $renewal = Renewal::with(['user', 'room', 'invoice.payments', 'booking'])
            ->findOrFail($id);

        return view('admin.renewals.show', compact('renewal'));
    }

    public function approve(Request $request, $id)
    {
        $renewal = Renewal::with(['user', 'room', 'booking'])->findOrFail($id);

        abort_if(
            $renewal->status !== 'pending_admin',
            422,
            'Hanya perpanjangan dengan status "Menunggu Admin" yang dapat disetujui.'
        );

        if ($renewal->payment_method === 'monthly') {
            return $this->approveMonthly($renewal);
        }

        return $this->approveFullpay($renewal);
    }

    private function approveFullpay(Renewal $renewal): \Illuminate\Http\RedirectResponse
    {
        $dueDate = now()->addDays(7);

        DB::transaction(function () use ($renewal, $dueDate) {
            if (!$renewal->invoice) {
                $roomPrice      = $renewal->room?->price ?? 0;
                $duration       = $renewal->duration_months;
                $baseAmount     = $roomPrice * $duration;
                $serviceMonthly = (int) ($renewal->service_amount_monthly ?? 0);
                $serviceAmount  = $serviceMonthly > 0
                    ? $serviceMonthly * $duration
                    : max(0, (int) $renewal->amount - $baseAmount);
                $totalAmount = (int) $renewal->amount > 0
                    ? (int) $renewal->amount
                    : $baseAmount + $serviceAmount;

                $periodStart = $renewal->current_end_date
                    ? Carbon::parse($renewal->current_end_date)->addDay()
                    : now();
                $periodEnd = $renewal->new_end_date
                    ? Carbon::parse($renewal->new_end_date)
                    : $periodStart->copy()->addMonths($duration);

                Invoice::create([
                    'user_id'        => $renewal->user_id,
                    'room_id'        => $renewal->room_id,
                    'renewal_id'     => $renewal->id,
                    'invoice_number' => 'INV-RNW-' . date('Ymd') . '-' . str_pad($renewal->id, 4, '0', STR_PAD_LEFT),
                    'invoice_type'   => 'renewal',
                    'base_amount'    => $baseAmount,
                    'service_amount' => $serviceAmount,
                    'total_amount'   => $totalAmount,
                    'tax_amount'     => round($totalAmount * 0.005, 2),
                    'status'         => 'unpaid',
                    'due_date'       => $dueDate,
                    'period_start'   => $periodStart,
                    'period_end'     => $periodEnd,
                    'notes'          => $this->buildInvoiceNotes($renewal),
                ]);
            }

            $renewal->update([
                'status'      => 'pending_payment',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);
        });

        $roomNumber = $renewal->room?->room_number ?? '-';
        $userName   = $renewal->user?->name ?? 'Penghuni';
        $durasi     = $renewal->duration_months;
        $newEndFmt  = $renewal->new_end_date
            ? Carbon::parse($renewal->new_end_date)->format('d M Y')
            : '-';
        $totalFmt   = 'Rp ' . number_format($renewal->amount, 0, ',', '.');
        $dueDateFmt = $dueDate->format('d M Y');

        InAppNotification::send(
            $renewal->user_id,
            'renewal_approved_pay_now',
            ' Perpanjangan Disetujui — Silakan Bayar',
            'Perpanjangan sewa kamar ' . $roomNumber .
                ' selama ' . $durasi . ' bulan telah disetujui. ' .
                'Silakan bayar sebelum ' . $dueDateFmt . '.',
            route('tenant.renewal.invoice', $renewal->id),
            ['renewal_id' => $renewal->id]
        );

        if ($renewal->user?->phone) {
            try {
                $waMsg = "Halo *{$userName}*,\n\n"
                    . " *Perpanjangan Sewa Disetujui!*\n\n"
                    . "Pengajuan perpanjangan sewa Anda telah disetujui oleh admin.\n\n"
                    . "━━━━━━━━━━━━━━━━\n"
                    . " Kamar         : *{$roomNumber}*\n"
                    . " Durasi Tambah : *{$durasi} bulan*\n"
                    . " Sewa Hingga   : *{$newEndFmt}*\n"
                    . " Total Tagihan : *{$totalFmt}*\n"
                    . " Jatuh Tempo   : *{$dueDateFmt}*\n"
                    . "━━━━━━━━━━━━━━━━\n\n"
                    . "Segera lakukan pembayaran sebelum jatuh tempo.\n\nTerima kasih ";
                (new WhatsAppService())->send($renewal->user->phone, $waMsg);
            } catch (\Throwable $e) {
                Log::warning('[RenewalController] WA approveFullpay: ' . $e->getMessage());
            }
        }

        return back()->with(
            'success',
            'Perpanjangan disetujui. Invoice Rp ' .
            number_format($renewal->amount, 0, ',', '.') .
            ' telah dikirim ke ' . $userName . '.'
        );
    }

    private function approveMonthly(Renewal $renewal): \Illuminate\Http\RedirectResponse
    {
        $roomPrice      = $renewal->room?->price ?? 0;
        $serviceMonthly = (int) ($renewal->service_amount_monthly ?? 0);
        $monthlyTotal   = $roomPrice + $serviceMonthly;
        $dueDate        = now()->addDays(3);

        $periodStart = $renewal->current_end_date
            ? Carbon::parse($renewal->current_end_date)->addDay()
            : now();
        $periodEnd = $periodStart->copy()->addMonth()->subDay();

        $booking = $renewal->booking
            ?? Booking::where('user_id', $renewal->user_id)
                    ->where('room_id', $renewal->room_id)
                    ->latest()
                    ->first();

        DB::transaction(function () use ($renewal, $booking, $dueDate, $roomPrice, $serviceMonthly, $monthlyTotal, $periodStart, $periodEnd) {
            Invoice::create([
                'user_id'        => $renewal->user_id,
                'room_id'        => $renewal->room_id,
                'renewal_id'     => $renewal->id,
                'invoice_number' => 'INV-RNW-' . date('Ymd') . '-' . str_pad($renewal->id, 4, '0', STR_PAD_LEFT) . '-M1',
                'invoice_type'   => 'renewal_monthly',
                'month_number'   => 1,
                'base_amount'    => $roomPrice,
                'service_amount' => $serviceMonthly,
                'total_amount'   => $monthlyTotal,
                'tax_amount'     => 0,
                'status'         => 'unpaid',
                'due_date'       => $dueDate,
                'period_start'   => $periodStart,
                'period_end'     => $periodEnd,
                'notes'          => $this->buildInvoiceNotes($renewal) .
                                    ' (Bulan 1 dari ' . $renewal->duration_months . ')',
            ]);

            $renewal->update([
                'status'                => 'pending_payment',
                'reviewed_by'           => auth()->id(),
                'reviewed_at'           => now(),
                'monthly_invoice_count' => 1,
            ]);

            if ($booking) {
                $booking->update([
                    'renewal_payment_method'  => 'monthly',
                    'renewal_monthly_count'   => 1,
                    'renewal_duration'        => $renewal->duration_months,
                    'renewal_id'              => $renewal->id,
                    'renewal_period_start'    => $periodStart->toDateString(),
                    'renewal_monthly_day'     => $periodStart->day,
                    'renewal_service_monthly' => $serviceMonthly,
                ]);
            }
        });

        $roomNumber = $renewal->room?->room_number ?? '-';
        $userName   = $renewal->user?->name ?? 'Penghuni';
        $durasi     = $renewal->duration_months;
        $newEndFmt  = $renewal->new_end_date
            ? Carbon::parse($renewal->new_end_date)->format('d M Y')
            : '-';
        $dueDateFmt = $dueDate->format('d M Y');

        InAppNotification::send(
            $renewal->user_id,
            'renewal_approved_monthly',
            ' Perpanjangan Disetujui — Bayar Bulan 1',
            'Perpanjangan sewa kamar ' . $roomNumber . ' (' . $durasi . ' bulan) disetujui. ' .
                'Silakan bayar tagihan bulan ke-1 sebelum ' . $dueDateFmt . '.',
            route('tenant.renewal.invoice', $renewal->id),
            ['renewal_id' => $renewal->id]
        );

        if ($renewal->user?->phone) {
            try {
                $totalFmt = 'Rp ' . number_format($monthlyTotal, 0, ',', '.');
                $waMsg = "Halo *{$userName}*,\n\n"
                    . " *Perpanjangan Sewa Disetujui! (Cicilan Bulanan)*\n\n"
                    . "━━━━━━━━━━━━━━━━\n"
                    . " Kamar         : *{$roomNumber}*\n"
                    . " Durasi Tambah : *{$durasi} bulan*\n"
                    . " Sewa Hingga   : *{$newEndFmt}*\n"
                    . " Cicilan/Bulan : *{$totalFmt}*\n"
                    . " Tagihan Bln 1 : *{$dueDateFmt}*\n"
                    . "━━━━━━━━━━━━━━━━\n\n"
                    . "Invoice bulan berikutnya dikirim otomatis tiap bulan.\n\nTerima kasih ";
                (new WhatsAppService())->send($renewal->user->phone, $waMsg);
            } catch (\Throwable $e) {
                Log::warning('[RenewalController] WA approveMonthly: ' . $e->getMessage());
            }
        }

        return back()->with(
            'success',
            'Perpanjangan bulanan disetujui. Invoice bulan 1 dari ' . $durasi .
            ' bulan telah dikirim ke ' . $userName .
            '. Invoice berikutnya dibuat otomatis tiap bulan.'
        );
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $renewal = Renewal::with(['user', 'room', 'booking'])->findOrFail($id);

        abort_if(
            !in_array($renewal->status, ['pending_admin', 'pending_payment']),
            422,
            'Status perpanjangan tidak valid untuk ditolak.'
        );

        DB::transaction(function () use ($renewal, $request) {
            Invoice::where('renewal_id', $renewal->id)
                ->whereIn('status', ['unpaid', 'overdue'])
                ->update(['status' => 'cancelled']);

            if ($renewal->booking) {
                $renewal->booking->update([
                    'renewal_id'             => null,
                    'renewal_payment_method' => null,
                    'renewal_period_start'   => null,
                    'renewal_duration'       => null,
                    'renewal_monthly_count'  => 0,
                ]);
            }

            $renewal->update([
                'status'           => 'rejected',
                'rejection_reason' => $request->reason,
                'reviewed_by'      => auth()->id(),
                'reviewed_at'      => now(),
            ]);
        });

        $roomNumber = $renewal->room?->room_number ?? '-';
        $userName   = $renewal->user?->name ?? 'Penghuni';
        $reason     = $request->reason;

        InAppNotification::send(
            $renewal->user_id,
            'renewal_rejected',
            ' Perpanjangan Sewa Ditolak',
            'Maaf, perpanjangan sewa kamar ' . $roomNumber .
                ' ditolak. Alasan: ' . $reason,
            route('tenant.renewal.index'),
            ['renewal_id' => $renewal->id]
        );

        if ($renewal->user?->phone) {
            try {
                $waMsg = "Halo *{$userName}*,\n\n"
                    . " *Perpanjangan Sewa Ditolak*\n\n"
                    . "Mohon maaf, pengajuan perpanjangan sewa Anda tidak dapat disetujui.\n\n"
                    . "━━━━━━━━━━━━━━━━\n"
                    . " Kamar  : *{$roomNumber}*\n"
                    . " Alasan : {$reason}\n"
                    . "━━━━━━━━━━━━━━━━\n\n"
                    . "Silakan hubungi admin untuk informasi lebih lanjut.\n\n"
                    . "Terima kasih ";
                (new WhatsAppService())->send($renewal->user->phone, $waMsg);
            } catch (\Throwable $e) {
                Log::warning('[RenewalController] WA reject (tenant): ' . $e->getMessage());
            }
        }

        $oldTenantUserId = $renewal->booking?->advance_for_user_id;
        if ($oldTenantUserId && $oldTenantUserId !== $renewal->user_id) {
            $oldTenant = User::find($oldTenantUserId);

            if ($oldTenant) {
                InAppNotification::send(
                    $oldTenant->id,
                    'renewal_rejected_old_tenant',
                    ' Perpanjangan Sewa Tidak Disetujui',
                    'Pengajuan perpanjangan sewa kamar ' . $roomNumber .
                        ' tidak disetujui admin. ' .
                        'Kamar akan diserahterimakan sesuai jadwal. ' .
                        'Hubungi admin untuk informasi lebih lanjut.',
                    route('tenant.dashboard'),
                    ['renewal_id' => $renewal->id]
                );

                if ($oldTenant->phone) {
                    try {
                        $waOld = "Halo *{$oldTenant->name}*,\n\n"
                            . " *Informasi Perpanjangan Sewa*\n\n"
                            . "Mohon maaf, pengajuan perpanjangan sewa kamar *{$roomNumber}* "
                            . "tidak dapat disetujui oleh admin.\n\n"
                            . "Kamar akan diserahterimakan kepada penghuni baru sesuai jadwal.\n\n"
                            . "Silakan hubungi admin untuk informasi lebih lanjut mengenai "
                            . "proses serah terima kamar.\n\n"
                            . "Terima kasih atas pengertian Anda ";
                        (new WhatsAppService())->send($oldTenant->phone, $waOld);
                    } catch (\Throwable $e) {
                        Log::warning('[RenewalController] WA reject (old tenant): ' . $e->getMessage());
                    }
                }
            }
        }

        return back()->with('success', 'Perpanjangan ditolak dan tenant telah dinotifikasi.');
    }

    public function finalApprove(Request $request, $id)
    {
        $renewal = Renewal::with(['user', 'room', 'invoice', 'booking'])->findOrFail($id);

        if ($renewal->status === 'approved') {
            return back()->with('info', 'Perpanjangan ini sudah disetujui sebelumnya.');
        }

        abort_if(
            $renewal->status !== 'pending_payment',
            422,
            'Perpanjangan ini belum dalam status menunggu konfirmasi pembayaran.'
        );

        if ($renewal->payment_method === 'monthly') {
            $paidCount = Invoice::where('renewal_id', $renewal->id)
                ->where('invoice_type', 'renewal_monthly')
                ->where('status', 'paid')
                ->count();

            if ($paidCount < $renewal->duration_months) {
                return back()->with(
                    'info',
                    'Renewal bulanan: sudah ' . $paidCount . ' dari ' .
                    $renewal->duration_months . ' bulan terbayar. ' .
                    'Renewal baru approved setelah semua bulan lunas.'
                );
            }
        }

        abort_if(
            !$renewal->invoice || $renewal->invoice->status !== 'paid',
            422,
            'Invoice belum lunas.'
        );

        $booking = $renewal->booking
            ?? Booking::where('user_id', $renewal->user_id)
                      ->where('room_id', $renewal->room_id)
                      ->whereIn('status', ['approved', 'active'])
                      ->latest()
                      ->first();

        abort_if(!$booking, 422, 'Booking aktif tidak ditemukan.');

        $newEndDate = Carbon::parse($renewal->new_end_date);

        DB::transaction(function () use ($renewal, $booking, $newEndDate) {
            $updateData = [
                'duration_months' => $booking->duration_months + $renewal->duration_months,
                'end_date'        => $newEndDate->toDateString(),
                'status'          => 'active',
            ];

            if ($renewal->additional_services !== null) {
                $updateData['additional_services'] = $renewal->additional_services;
            }

            $booking->update($updateData);

            $renewal->update([
                'status'      => 'approved',
                'approved_at' => now(),
            ]);
        });

        InAppNotification::send(
            $renewal->user_id,
            'renewal_final_approved',
            ' Sewa Berhasil Diperpanjang!',
            'Sewa kamar ' . ($renewal->room?->room_number ?? '-') .
                ' diperpanjang ' . $renewal->duration_months . ' bulan. ' .
                'Berakhir: ' . $newEndDate->format('d M Y') . '.',
            route('tenant.renewal.invoice', $renewal->id),
            ['renewal_id' => $renewal->id]
        );

        return back()->with(
            'success',
            'Perpanjangan dikonfirmasi! Sewa ' . $renewal->user?->name .
            ' berakhir: ' . $newEndDate->format('d M Y') . '.'
        );
    }

    public function sendOffer(Request $request, $id)
    {
        $renewal = Renewal::with(['user', 'room'])->findOrFail($id);

        InAppNotification::send(
            $renewal->user_id,
            'renewal_offer',
            ' Tawaran Perpanjangan Sewa',
            'Admin menawarkan perpanjangan sewa kamar ' . $renewal->room?->room_number . '.',
            route('tenant.renewal.show', $renewal->id),
            ['renewal_id' => $renewal->id]
        );

        return back()->with('success', 'Penawaran perpanjangan dikirim ke ' . $renewal->user?->name . '.');
    }

    private function buildInvoiceNotes(Renewal $renewal): ?string
    {
        $lines = [
            'Perpanjangan sewa kamar ' . ($renewal->room?->room_number ?? '') .
            ' selama ' . $renewal->duration_months . ' bulan.',
        ];

        if ($renewal->new_end_date) {
            $lines[] = 'Masa sewa baru berakhir: ' . Carbon::parse($renewal->new_end_date)->format('d M Y') . '.';
        }

        if ($renewal->additional_services && count($renewal->additional_services) > 0) {
            $svcList = [];
            foreach ($renewal->additional_services as $key => $svc) {
                $label     = $svc['label'] ?? $key;
                $price     = isset($svc['price']) ? 'Rp ' . number_format($svc['price'], 0, ',', '.') . '/bln' : '';
                $svcList[] = $label . ($price ? " ({$price})" : '');
            }
            $lines[] = 'Layanan tambahan: ' . implode(', ', $svcList);
        }

        return implode("\n", $lines);
    }
}