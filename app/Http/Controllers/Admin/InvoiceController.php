<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\InAppNotification;
use App\Models\Payment;
use App\Models\Renewal;
use App\Models\Role;
use App\Models\Room;
use App\Models\Ticket;
use App\Models\User;
use App\Models\WaitingList;
use App\Services\NotificationService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['user', 'room'])
            ->orderBy('due_date', 'asc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', '%' . $search . '%')
                                                   ->orWhere('email', 'like', '%' . $search . '%'))
                  ->orWhere('invoice_number', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('month')) {
            $query->whereMonth('period_start', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('period_start', $request->year);
        }

        $invoices = $query->paginate(15)->withQueryString();

        $stats = [
            'unpaid'        => Invoice::where('status', 'unpaid')->count(),
            'overdue'       => Invoice::where('status', 'overdue')
                                ->orWhere(fn($q) => $q->where('status', 'unpaid')
                                    ->where('due_date', '<', now()))
                                ->count(),
            'pending'       => Invoice::where('status', 'pending_verification')->count(),
            'paid'          => Invoice::where('status', 'paid')->count(),
            'total_revenue' => Invoice::where('status', 'paid')->sum('total_amount'),
        ];

        $user           = auth()->user();
        $adminName      = $user->name ?? 'Admin';
        $adminInitial   = strtoupper(substr($adminName, 0, 1));
        $unread         = $user->unreadNotifications->count();
        $pendingBook    = Booking::where('status', 'pending')->count();
        $pendingRenewal = Renewal::whereIn('status', ['pending_admin', 'pending_payment'])->count();
        $pendingPay     = Invoice::where('status', 'pending_verification')->count();
        $openTkt        = Ticket::whereIn('status', ['open', 'in_progress'])->count();

        return view('admin.invoices.index', compact(
            'invoices', 'stats',
            'adminName', 'adminInitial', 'unread',
            'pendingBook', 'pendingRenewal', 'pendingPay', 'openTkt'
        ));
    }

    public function create()
    {
        $tenants = User::whereHas('role', fn($q) => $q->whereIn('name', ['tenant', 'prospective_tenant']))
            ->where('is_active', true)
            ->with([
                'bookings' => fn($q) => $q->whereIn('status', ['approved', 'active'])->with('room')
            ])
            ->orderBy('name')
            ->get();

        return view('admin.invoices.create', compact('tenants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'        => 'required|exists:users,id',
            'room_id'        => 'required|exists:rooms,id',
            'base_amount'    => 'required|numeric|min:0',
            'service_amount' => 'nullable|numeric|min:0',
            'period_start'   => 'required|date',
            'period_end'     => 'required|date|after:period_start',
            'due_date'       => 'required|date',
            'notes'          => 'nullable|string|max:1000',
            'invoice_title'  => 'nullable|string|max:255',
        ]);

        $serviceAmount = $request->service_amount ?? 0;
        $totalAmount   = $request->base_amount + $serviceAmount;
        $taxAmount     = round($totalAmount * 0.005, 2);

        $invoice = Invoice::create([
            'user_id'        => $request->user_id,
            'room_id'        => $request->room_id,
            'invoice_type'   => 'manual',
            'base_amount'    => $request->base_amount,
            'service_amount' => $serviceAmount,
            'total_amount'   => $totalAmount,
            'tax_amount'     => $taxAmount,
            'period_start'   => $request->period_start,
            'period_end'     => $request->period_end,
            'due_date'       => $request->due_date,
            'status'         => 'unpaid',
            'notes'          => $request->notes,
        ]);

        $user      = $invoice->user;
        $room      = $invoice->room;
        $dueFmt    = \Carbon\Carbon::parse($request->due_date)->format('d M Y');
        $totalFmt  = 'Rp ' . number_format($totalAmount, 0, ',', '.');
        $title     = $request->invoice_title ?: 'Invoice Lainnya';
        $roleName  = $user?->role?->name ?? 'tenant';
        $showRoute = match($roleName) {
            'tenant'             => route('tenant.invoices.show', $invoice->id),
            'prospective_tenant' => route('prospective.invoices.show', $invoice->id),
            default              => route('tenant.invoices.show', $invoice->id),
        };

        InAppNotification::send(
            $invoice->user_id,
            'invoice_created_manual',
            " {$title} — {$invoice->invoice_number}",
            "Invoice tambahan senilai {$totalFmt} untuk kamar " . ($room?->room_number ?? '-') .
                " telah diterbitkan admin. Jatuh tempo: {$dueFmt}." .
                ($request->notes ? " Keterangan: {$request->notes}" : ''),
            $showRoute,
            ['invoice_id' => $invoice->id]
        );

        if ($user?->phone) {
            try {
                $wa    = new WhatsAppService();
                $waMsg = "Halo *{$user->name}*,\n\n"
                    . " *{$title}*\n\n"
                    . "Admin Urban Kost telah menerbitkan tagihan tambahan untuk Anda.\n\n"
                    . "━━━━━━━━━━━━━━━━\n"
                    . " No. Invoice : *{$invoice->invoice_number}*\n"
                    . " Kamar       : *" . ($room?->room_number ?? '-') . "*\n"
                    . " Total        : *{$totalFmt}*\n"
                    . " Jatuh Tempo  : *{$dueFmt}*\n"
                    . ($request->notes ? " Keterangan   : {$request->notes}\n" : '')
                    . "━━━━━━━━━━━━━━━━\n\n"
                    . "Silakan lakukan pembayaran sebelum jatuh tempo dan upload bukti transfer melalui aplikasi.\n\n"
                    . config('app.url') . "/calon-penghuni/tagihan/{$invoice->id}\n\n"
                    . "Terima kasih ";

                $wa->send($user->phone, $waMsg);
                Log::info('[InvoiceController] WA invoice manual terkirim ke ' . $user->phone);
            } catch (\Exception $e) {
                Log::warning('[InvoiceController] WA invoice manual gagal: ' . $e->getMessage());
            }
        }

        $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))
            ->where('id', '!=', auth()->id())
            ->get();

        foreach ($admins as $admin) {
            InAppNotification::send(
                $admin->id,
                'invoice_created_manual_admin',
                " Invoice Manual Dibuat — {$invoice->invoice_number}",
                auth()->user()->name . " membuat invoice manual ({$title}) untuk " .
                    ($user?->name ?? '-') . " kamar " . ($room?->room_number ?? '-') .
                    " senilai {$totalFmt}.",
                route('admin.invoices.show', $invoice->id),
                ['invoice_id' => $invoice->id]
            );
        }

        return redirect()->route('admin.invoices.show', $invoice->id)
            ->with('success', ' Invoice <strong>' . $invoice->invoice_number . '</strong> berhasil dibuat dan notifikasi telah dikirim ke penghuni.');
    }

    public function show($id)
    {
        $invoice  = Invoice::with(['user', 'room', 'payments.verifier'])->findOrFail($id);

        $payments = $invoice->payments()
            ->whereNotIn('status', ['initiated'])
            ->orderBy('created_at', 'desc')
            ->get();

        $relatedWl = null;
        if (in_array($invoice->invoice_type, ['waiting_list_dp', 'waiting_list_remaining'])) {
            $relatedWl = $this->lookupWaitingList($invoice);
        }

        return view('admin.invoices.show', compact('invoice', 'payments', 'relatedWl'));
    }

    public function edit($id)
    {
        $invoice = Invoice::with(['user', 'room'])->findOrFail($id);

        if (!in_array($invoice->status, ['unpaid', 'overdue'])) {
            return redirect()->route('admin.invoices.show', $id)
                ->with('error', ' Invoice yang sudah dibayar atau diverifikasi tidak dapat diedit.');
        }

        $tenants = User::whereHas('role', fn($q) => $q->whereIn('name', ['tenant', 'prospective_tenant']))
            ->where('is_active', true)
            ->with([
                'bookings' => fn($q) => $q->whereIn('status', ['approved', 'active'])->with('room')
            ])
            ->orderBy('name')
            ->get();

        return view('admin.invoices.edit', compact('invoice', 'tenants'));
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        if (!in_array($invoice->status, ['unpaid', 'overdue'])) {
            return redirect()->route('admin.invoices.show', $id)
                ->with('error', ' Invoice yang sudah diproses tidak dapat diubah.');
        }

        $request->validate([
            'base_amount'    => 'required|numeric|min:0',
            'service_amount' => 'nullable|numeric|min:0',
            'period_start'   => 'required|date',
            'period_end'     => 'required|date|after:period_start',
            'due_date'       => 'required|date',
            'notes'          => 'nullable|string|max:1000',
            'invoice_title'  => 'nullable|string|max:255',
        ]);

        $serviceAmount = $request->service_amount ?? 0;
        $totalAmount   = $request->base_amount + $serviceAmount;
        $taxAmount     = round($totalAmount * 0.005, 2);

        $oldTotal = $invoice->total_amount;

        $invoice->update([
            'base_amount'    => $request->base_amount,
            'service_amount' => $serviceAmount,
            'total_amount'   => $totalAmount,
            'tax_amount'     => $taxAmount,
            'period_start'   => $request->period_start,
            'period_end'     => $request->period_end,
            'due_date'       => $request->due_date,
            'notes'          => $request->notes,
        ]);

        if ($oldTotal != $totalAmount) {
            $invoice->payments()
                ->where('status', 'initiated')
                ->where('method', 'midtrans')
                ->update(['status' => 'rejected']);

            Log::info('[InvoiceController] Snap token initiated dibatalkan karena nominal invoice diubah.', [
                'invoice_id' => $invoice->id,
                'old_total'  => $oldTotal,
                'new_total'  => $totalAmount,
            ]);
        }

        $user      = $invoice->user;
        $room      = $invoice->room;
        $totalFmt  = 'Rp ' . number_format($totalAmount, 0, ',', '.');
        $dueFmt    = \Carbon\Carbon::parse($request->due_date)->format('d M Y');
        $title     = $request->invoice_title ?: 'Invoice Diperbarui';
        $roleName  = $user?->role?->name ?? 'tenant';
        $showRoute = match($roleName) {
            'tenant'             => route('tenant.invoices.show', $invoice->id),
            'prospective_tenant' => route('prospective.invoices.show', $invoice->id),
            default              => route('tenant.invoices.show', $invoice->id),
        };

        if ($oldTotal != $totalAmount || $request->notes) {
            InAppNotification::send(
                $invoice->user_id,
                'invoice_updated',
                " Invoice Diperbarui — {$invoice->invoice_number}",
                "Invoice {$invoice->invoice_number} telah diperbarui oleh admin. " .
                    "Total tagihan: {$totalFmt}. Jatuh tempo: {$dueFmt}." .
                    ($request->notes ? " Ket: {$request->notes}" : ''),
                $showRoute,
                ['invoice_id' => $invoice->id]
            );

            if ($user?->phone) {
                try {
                    $wa = new WhatsAppService();
                    $waMsg = "Halo *{$user->name}*,\n\n" .
                        " *Invoice Anda Diperbarui*\n\n" .
                        "Invoice *{$invoice->invoice_number}* telah diperbarui oleh admin.\n\n" .
                        "━━━━━━━━━━━━━━━━\n" .
                        " Total Baru   : *{$totalFmt}*\n" .
                        " Jatuh Tempo  : *{$dueFmt}*\n" .
                        ($request->notes ? " Keterangan   : {$request->notes}\n" : '') .
                        "━━━━━━━━━━━━━━━━\n\n" .
                        "Hubungi admin jika ada pertanyaan ";

                    if (method_exists($wa, 'sendText')) {
                        $wa->sendText($user->phone, $waMsg);
                    } elseif (method_exists($wa, 'sendMessage')) {
                        $wa->sendMessage($user->phone, $waMsg);
                    } elseif (method_exists($wa, 'send')) {
                        $wa->send($user->phone, $waMsg);
                    }
                } catch (\Exception $e) {
                    Log::warning('[InvoiceController] WA update invoice gagal: ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('admin.invoices.show', $invoice->id)
            ->with('success', ' Invoice <strong>' . $invoice->invoice_number . '</strong> berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);

        if (!in_array($invoice->status, ['unpaid', 'cancelled', 'overdue'])) {
            return redirect()->route('admin.invoices.index')
                ->with('error', ' Invoice yang sudah dibayar atau dalam proses verifikasi tidak dapat dihapus.');
        }

        $hasRealPayment = $invoice->payments()
            ->whereNotIn('status', ['initiated'])
            ->exists();

        if ($hasRealPayment) {
            return redirect()->route('admin.invoices.index')
                ->with('error', ' Invoice yang sudah memiliki bukti pembayaran tidak dapat dihapus. Tolak pembayaran terlebih dahulu.');
        }

        $invoiceNumber = $invoice->invoice_number;
        $userName      = $invoice->user?->name;

        InAppNotification::send(
            $invoice->user_id,
            'invoice_deleted',
            ' Invoice Dibatalkan',
            "Invoice {$invoiceNumber} telah dibatalkan oleh admin. Hubungi admin jika ada pertanyaan.",
            route('home'),
            ['invoice_number' => $invoiceNumber]
        );

        $invoice->payments()->delete();
        $invoice->delete();

        return redirect()->route('admin.invoices.index')
            ->with('success', " Invoice <strong>{$invoiceNumber}</strong> ({$userName}) berhasil dihapus.");
    }

    public function verifyPayment(Request $request, $invoiceId)
    {
        $invoice = Invoice::with(['user.role', 'room', 'booking', 'waitingList'])->findOrFail($invoiceId);
        $notif   = new NotificationService();
 
        if ($invoice->status === 'paid') {
            return back()->with('info', 'Invoice ini sudah berstatus lunas.');
        }
 
        $invoice->update([
            'status'      => 'paid',
            'paid_at'     => now(),
            'verified_by' => auth()->id(),
            'notes'       => $request->filled('notes') ? $request->notes : $invoice->notes,
        ]);
 
        $invoiceNum  = $invoice->invoice_number;
        $invoiceType = $invoice->invoice_type ?? '';
        $isDP        = str_starts_with($invoiceNum, 'INV-DP-')    || $invoiceType === 'waiting_list_dp';
        $isSisa      = str_starts_with($invoiceNum, 'INV-SISA-')  || $invoiceType === 'waiting_list_remaining';
        $isMonthly   = $invoiceType === 'monthly';
        $isMonthlyFirst = $invoiceType === 'monthly_first';        
 
        $wl = $this->lookupWaitingList($invoice);
 
        if ($wl && !$invoice->waiting_list_id) {
            $invoice->update(['waiting_list_id' => $wl->id]);
        }
 
        if ($isDP) {
            InAppNotification::send(
                $invoice->user_id,
                'dp_payment_verified',
                ' DP Waiting List Diterima',
                'DP Anda sebesar Rp ' . number_format($invoice->total_amount, 0, ',', '.') .
                    ' untuk kamar ' . $invoice->room?->room_number . ' sudah diterima. Posisi antrian Anda aman.',
                route('prospective.invoices.index')
            );
 
            return back()->with('success',
                ' DP <strong>' . $invoice->invoice_number . '</strong> lunas. ' .
                'Calon penghuni telah dinotifikasi.'
            );
        }
 
        if ($isSisa) {
            $invoice->refresh();
            $invoice->load(['user.role', 'room', 'booking.room', 'waitingList.booking']);
            return $this->handleWaitingListRemainingVerified($invoice, $notif);
        }
 
        if ($isMonthlyFirst) {
            return $this->handleMonthlyFirstInvoicePaid($invoice, $notif);
        }
 
        if ($isMonthly) {
            return $this->handleMonthlyInvoicePaid($invoice, $notif);
        }
 
        $invoice->room?->update(['status' => 'occupied']);
 
        $user     = $invoice->user;
        $upgraded = false;
 
        if ($user && $user->role?->name === 'prospective_tenant') {
            $tenantRole = Role::where('name', 'tenant')->firstOrFail();
            $user->update(['role_id' => $tenantRole->id, 'is_active' => true]);
            \App\Http\Controllers\Auth\LoginController::generateAndSendOtp($user->email, 'login', $user->name);
            $notif->notifyTenantUpgraded($user, $invoice->room);
            $upgraded = true;
        }
 
        if ($invoice->booking && $invoice->booking->status === 'approved') {
            $invoice->booking->update(['status' => 'active']);
        }
 
        $notif->notifyInvoicePaid($invoice);
 
        $msg = ' Invoice <strong>' . $invoice->invoice_number . '</strong> lunas. ' .
               'Kamar <strong>' . $invoice->room?->room_number . '</strong> → Occupied.';
        if ($upgraded) {
            $msg .= ' <strong>' . $user->name . '</strong> diupgrade menjadi Penghuni Aktif.';
        }
 
        return back()->with('success', $msg);
    }

    private function handleMonthlyFirstInvoicePaid(Invoice $invoice, NotificationService $notif): \Illuminate\Http\RedirectResponse
    {
        $user     = $invoice->user;
        $upgraded = false;
 
        $invoice->room?->update(['status' => 'occupied']);
 
        $booking = $invoice->booking ?? \App\Models\Booking::find($invoice->booking_id);

        if ($booking) {
            $checkIn    = $booking->check_in_date ?? now();
            $billingDay = min((int) \Carbon\Carbon::parse($checkIn)->format('d'), 28);

            \App\Models\Booking::where('id', $booking->id)->update([
                'status'                => 'active',
                'monthly_billing_day'   => $billingDay,
                'monthly_invoice_count' => 1,
            ]);
        }
 
        if ($user && $user->role?->name === 'prospective_tenant') {
            $tenantRole = Role::where('name', 'tenant')->firstOrFail();
            $user->update(['role_id' => $tenantRole->id, 'is_active' => true]);
            \App\Http\Controllers\Auth\LoginController::generateAndSendOtp($user->email, 'login', $user->name);
            $notif->notifyTenantUpgraded($user, $invoice->room);
            $upgraded = true;
        }
 
        $notif->notifyInvoicePaid($invoice);
 
        $billingDay   = $invoice->booking?->monthly_billing_day ?? (int) now()->format('d');
        $nextBillDate = Carbon::parse($invoice->period_end?->addDay())->format('d M Y');
 
        InAppNotification::send(
            $invoice->user_id,
            'monthly_first_paid',
            ' Pembayaran Bulan Pertama Dikonfirmasi!',
            'Anda resmi menghuni kamar ' . $invoice->room?->room_number . '. ' .
                'Tagihan bulan berikutnya akan dikirim otomatis sebelum tanggal ' . $billingDay . ' tiap bulan.',
            route('tenant.invoices.index'),
            ['invoice_id' => $invoice->id]
        );
 
        $msg = ' Invoice bulan pertama <strong>' . $invoice->invoice_number . '</strong> lunas. ' .
               'Kamar <strong>' . $invoice->room?->room_number . '</strong> → Occupied. ' .
               'Invoice bulanan berikutnya akan dibuat otomatis oleh sistem.';
 
        if ($upgraded) {
            $msg .= ' <strong>' . $user->name . '</strong> diupgrade menjadi Penghuni Aktif.';
        }
 
        return back()->with('success', $msg);
    }
 
    private function handleMonthlyInvoicePaid(Invoice $invoice, NotificationService $notif): \Illuminate\Http\RedirectResponse
    {
        $booking = $invoice->booking;
 
        if ($booking) {
            $monthNumber = $invoice->month_number ?? 0;
            if ($monthNumber > ($booking->monthly_invoice_count ?? 0)) {
                $booking->update(['monthly_invoice_count' => $monthNumber]);
            }
 
            $isLastMonth = ($monthNumber >= $booking->duration_months);
 
            if ($isLastMonth) {
                $booking->update([
                    'status'   => 'completed',
                    'end_date' => $invoice->period_end?->toDateString() ?? now()->toDateString(),
                ]);
 
                InAppNotification::send(
                    $invoice->user_id,
                    'monthly_last_paid',
                    ' Pelunasan Akhir Dikonfirmasi — Masa Sewa Selesai',
                    'Pembayaran bulan terakhir untuk kamar ' . $invoice->room?->room_number .
                        ' dikonfirmasi. Masa sewa Anda resmi berakhir pada ' .
                        $invoice->period_end?->format('d M Y') . '.',
                    route('tenant.invoices.show', $invoice->id)
                );
            } else {
                InAppNotification::send(
                    $invoice->user_id,
                    'monthly_invoice_paid',
                    ' Tagihan Bulan ke-' . $monthNumber . ' Dikonfirmasi',
                    'Tagihan bulan ke-' . $monthNumber . ' untuk kamar ' . $invoice->room?->room_number .
                        ' telah lunas. Tagihan bulan berikutnya akan dikirim otomatis.',
                    route('tenant.invoices.show', $invoice->id)
                );
            }
        }
 
        $notif->notifyInvoicePaid($invoice);
 
        $isLast = ($invoice->month_number ?? 0) >= ($booking?->duration_months ?? 0);
 
        return back()->with('success',
            ' Invoice bulanan <strong>' . $invoice->invoice_number . '</strong> (bulan ke-' . $invoice->month_number . ') lunas.' .
            ($isLast ? ' Masa sewa booking ini telah <strong>selesai</strong>.' : ' Tagihan bulan berikutnya otomatis dibuat oleh sistem.')
        );
    }

    public function rejectPayment(Request $request, $paymentId)
    {
        $request->validate(['rejection_reason' => 'required|string|max:255']);

        $payment = Payment::with('invoice.user')->findOrFail($paymentId);

        if ($payment->status === 'initiated') {
            return back()->with(
                'error',
                ' Tidak ada bukti pembayaran yang bisa ditolak. ' .
                'Pengguna hanya membuka popup Midtrans dan belum menyelesaikan transaksi.'
            );
        }

        if ($payment->status !== 'pending') {
            return back()->with(
                'error',
                ' Pembayaran ini tidak dapat ditolak. Status saat ini: ' . $payment->status . '.'
            );
        }

        $payment->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'verified_by'      => auth()->id(),
        ]);

        $invoice = $payment->invoice;
        $invoice->update(['status' => 'unpaid']);

        $userId    = $invoice->user_id;
        $user      = $invoice->user;
        $roleMap   = [
            'tenant'             => 'tenant.invoices.show',
            'prospective_tenant' => 'prospective.invoices.show',
        ];
        $roleName  = $user?->role?->name ?? 'tenant';
        $showRoute = $roleMap[$roleName] ?? 'tenant.invoices.show';

        InAppNotification::send(
            $userId,
            'payment_rejected',
            ' Bukti Pembayaran Ditolak',
            'Bukti pembayaran untuk invoice ' . $invoice->invoice_number .
                ' ditolak. Alasan: ' . $request->rejection_reason .
                '. Silakan upload ulang bukti yang benar.',
            route($showRoute, $invoice->id),
            ['payment_id' => $payment->id, 'invoice_id' => $invoice->id]
        );

        return back()->with('error', 'Bukti pembayaran ditolak. Penghuni telah dinotifikasi.');
    }

    private function handleWaitingListDpVerified(Invoice $invoice, NotificationService $notif): \Illuminate\Http\RedirectResponse
    {
        $wl = $this->lookupWaitingList($invoice);

        if ($wl && !$invoice->waiting_list_id) {
            $invoice->update(['waiting_list_id' => $wl->id]);
        }

        InAppNotification::send(
            $invoice->user_id,
            'dp_payment_verified',
            ' DP Waiting List Diterima',
            'DP Anda sebesar Rp ' . number_format($invoice->total_amount, 0, ',', '.') .
                ' untuk kamar ' . ($invoice->room?->room_number ?? '-') . ' sudah diterima. ' .
                'Posisi antrian Anda aman.',
            route('prospective.invoices.index')
        );

        if ($wl) {
            if (!$wl->dp_paid || $wl->dp_paid <= 0) {
                $wl->update(['dp_paid' => $invoice->total_amount]);
                $wl->refresh();
            }
            try {
                $notif->notifyWaitingListDpPaid($wl);
            } catch (\Exception $e) {
                Log::warning('[InvoiceController] notifyWaitingListDpPaid gagal: ' . $e->getMessage());
            }
        } else {
            $user = $invoice->user;
            if ($user?->phone) {
                try {
                    (new WhatsAppService())->sendPaymentConfirmed($user->phone, [
                        'name'           => $user->name,
                        'invoice_number' => $invoice->invoice_number,
                        'amount'         => 'Rp ' . number_format($invoice->total_amount, 0, ',', '.'),
                        'period'         => $invoice->period_start?->format('F Y'),
                        'room'           => $invoice->room?->room_number,
                        'verified_at'    => now()->format('d M Y') . ' ',
                    ]);
                } catch (\Exception $e) {
                    Log::warning('[InvoiceController] WA fallback DP gagal: ' . $e->getMessage());
                }
            }
        }

        return back()->with('success',
            ' DP <strong>' . $invoice->invoice_number . '</strong> lunas. Calon penghuni telah dinotifikasi via in-app dan WhatsApp.'
        );
    }

    private function handleWaitingListRemainingVerified(Invoice $invoice, NotificationService $notif): \Illuminate\Http\RedirectResponse
    {
        $bookingForWl = null;
        if ($invoice->booking_id) {
            $bookingForWl = \App\Models\Booking::find($invoice->booking_id);
        }
        
        $wl = $this->lookupWaitingList($invoice);
        
        if (!$bookingForWl && $wl?->booking_id) {
            $bookingForWl = \App\Models\Booking::find($wl->booking_id);
        }

        $user          = $invoice->user;
        $upgraded      = false;
        $roomId        = $invoice->room_id;
        $oldTenantName = null;

        $oldTenantUserId = $wl?->booking?->advance_for_user_id
            ?? $invoice->booking?->advance_for_user_id;

        $paymentMethod = $bookingForWl?->payment_method ?? 'lump_sum';

        $checkInSource = $bookingForWl?->check_in_date ?? now();
        $billingDay    = min((int) \Carbon\Carbon::parse($checkInSource)->format('d'), 28);

        DB::transaction(function () use (
            $invoice, $wl, $user, $roomId,
            $oldTenantUserId, &$upgraded, &$oldTenantName,
            $notif, $bookingForWl, $paymentMethod, $billingDay
        ) {
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

                if ($oldTenantUser) {
                    $stillHasRoom = Booking::where('user_id', $oldTenantUser->id)
                        ->whereIn('status', ['approved', 'active'])
                        ->where('id', '!=', $oldBooking->id)
                        ->exists();

                    if (!$stillHasRoom && $oldTenantUser->role?->name === 'tenant') {
                        $prospectiveRole = Role::where('name', 'prospective_tenant')->first()
                            ?? Role::where('name', 'ex_tenant')->first();

                        if ($prospectiveRole) {
                            $oldTenantUser->update(['role_id' => $prospectiveRole->id]);
                        }
                        $oldTenantName = $oldTenantUser->name;

                        InAppNotification::send(
                            $oldTenantUser->id,
                            'tenancy_ended',
                            ' Masa Sewa Kamar Telah Berakhir',
                            'Masa sewa kamar ' . ($invoice->room?->room_number ?? '-') .
                                ' Anda telah resmi berakhir dan diserahterimakan ke penghuni baru.',
                            route('home')
                        );

                        try {
                            $notif->notifyTenancyEnded($oldTenantUser, $invoice->room);
                        } catch (\Exception $e) {
                            Log::warning('[InvoiceController] notifyTenancyEnded gagal: ' . $e->getMessage());
                        }
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

            if ($user && in_array($user->role?->name, ['prospective_tenant', 'prospective'])) {
                $tenantRole = Role::where('name', 'tenant')->firstOrFail();
                $user->update(['role_id' => $tenantRole->id, 'is_active' => true]);
                LoginController::generateAndSendOtp($user->email, 'login', $user->name);

                try {
                    $notif->notifyTenantUpgraded($user, $invoice->room);
                } catch (\Exception $e) {
                    Log::warning('[InvoiceController] notifyTenantUpgraded gagal: ' . $e->getMessage());
                }

                $upgraded = true;
            }

            if ($bookingForWl) {
                $bookingUpdate = ['status' => 'active', 'room_id' => $roomId];

                if (empty($bookingForWl->check_in_date)) {
                    $bookingUpdate['check_in_date'] = now()->toDateString();
                }

                if ($paymentMethod === 'monthly') {
                    $bookingUpdate['monthly_billing_day']   = $billingDay;
                    $bookingUpdate['monthly_invoice_count'] = 1;
                }

                \App\Models\Booking::where('id', $bookingForWl->id)->update($bookingUpdate);

                Log::info('[InvoiceController] Booking #' . $bookingForWl->id .
                    ' → active. PaymentMethod: ' . $paymentMethod .
                    '. BillingDay: ' . $billingDay);
            } else {
                Log::error('[InvoiceController] handleWaitingListRemainingVerified: bookingForWl null!', [
                    'invoice_id' => $invoice->id,
                    'booking_id' => $invoice->booking_id,
                    'wl_id'      => $wl?->id,
                ]);
            }

            WaitingList::where('room_id', $roomId)
                ->whereIn('status', ['waiting', 'notified'])
                ->update([
                    'status'       => 'converted',
                    'converted_at' => now(),
                ]);

            if ($wl && !$invoice->waiting_list_id) {
                $invoice->update(['waiting_list_id' => $wl->id]);
            }

            $invoice->room?->update(['status' => 'occupied']);
        });

        if ($oldTenantUserId) {
            InAppNotification::send(
                $oldTenantUserId,
                'room_handover_complete',
                ' Penghuni Baru Sudah Aktif',
                'Kamar ' . ($invoice->room?->room_number ?? '-') . ' kini resmi ditempati penghuni baru.',
                route('home')
            );

            try {
                $notif->notifyOldTenantRoomHandover($oldTenantUserId, $invoice->room);
            } catch (\Exception $e) {
                Log::warning('[InvoiceController] notifyOldTenantRoomHandover gagal: ' . $e->getMessage());
            }
        }

        InAppNotification::send(
            $invoice->user_id,
            'tenancy_activated',
            ' Selamat! Anda Resmi Menjadi Penghuni',
            'Pelunasan untuk kamar ' . ($invoice->room?->room_number ?? '-') .
                ' dikonfirmasi. Hubungi admin untuk serah terima kunci.',
            route('tenant.dashboard')
        );

        $msg = ' Pelunasan <strong>' . $invoice->invoice_number . '</strong> lunas. ' .
            'Kamar <strong>' . ($invoice->room?->room_number ?? '-') . '</strong> → Occupied.';

        if ($upgraded) {
            $msg .= ' <strong>' . $invoice->user?->name . '</strong> diupgrade menjadi Penghuni Aktif.';
        }
        if ($oldTenantName) {
            $msg .= ' Penghuni lama <strong>' . $oldTenantName . '</strong> telah diselesaikan statusnya.';
        }

        if ($paymentMethod === 'monthly') {
            InAppNotification::send(
                $invoice->user_id,
                'monthly_schedule_info',
                ' Jadwal Tagihan Bulanan Anda',
                'Tagihan bulan ke-2 dan seterusnya akan dikirim otomatis setiap tanggal ' .
                    $billingDay . ' tiap bulan.',
                route('tenant.invoices.index'),
                ['booking_id' => $invoice->booking_id]
            );
            $msg .= ' Tagihan bulanan otomatis setiap tanggal <strong>' . $billingDay . '</strong>.';
        }

        return back()->with('success', $msg);
    }

    private function handleRenewalVerified(Invoice $invoice, NotificationService $notif): void
    {
        $renewal = Renewal::with(['user', 'room', 'booking'])->find($invoice->renewal_id);

        if (!$renewal || $renewal->status !== 'pending_payment') {
            return;
        }

        $booking = $renewal->booking
            ?? Booking::where('user_id', $renewal->user_id)
                ->where('room_id', $renewal->room_id)
                ->whereIn('status', ['approved', 'active'])
                ->latest()
                ->first();

        if ($booking) {
            $newEndDate = \Carbon\Carbon::parse($renewal->new_end_date);
            $updateData = [
                'duration_months' => $booking->duration_months + $renewal->duration_months,
                'end_date'        => $newEndDate->toDateString(),
                'status'          => 'active',
            ];
            if ($renewal->additional_services !== null) {
                $updateData['additional_services'] = $renewal->additional_services;
            }
            $booking->update($updateData);
        }

        $renewal->update([
            'status'      => 'approved',
            'approved_at' => now(),
        ]);

        $newEndFmt  = \Carbon\Carbon::parse($renewal->new_end_date)->format('d M Y');
        $totalFmt   = 'Rp ' . number_format($invoice->total_amount, 0, ',', '.');
        $roomNumber = $renewal->room?->room_number ?? '-';
        $userName   = $renewal->user?->name ?? 'Penghuni';
        $durasi     = $renewal->duration_months;

        InAppNotification::send(
            $renewal->user_id,
            'renewal_final_approved',
            ' Sewa Berhasil Diperpanjang!',
            'Pembayaran perpanjangan kamar ' . $roomNumber .
                ' telah diverifikasi. Sewa diperpanjang ' . $durasi . ' bulan' .
                ' hingga ' . $newEndFmt . '.',
            route('tenant.renewal.invoice', $renewal->id)
        );

        if ($renewal->user?->phone) {
            try {
                $waMsg = "Halo *{$userName}*,\n\n"
                    . " *Perpanjangan Sewa Berhasil Diverifikasi!*\n\n"
                    . "Pembayaran Anda telah dikonfirmasi oleh admin.\n\n"
                    . "━━━━━━━━━━━━━━━━\n"
                    . " Kamar         : *{$roomNumber}*\n"
                    . " Durasi Tambah : *{$durasi} bulan*\n"
                    . " Sewa Hingga   : *{$newEndFmt}*\n"
                    . " Total Bayar   : *{$totalFmt}*\n"
                    . "━━━━━━━━━━━━━━━━\n\n"
                    . "Terima kasih telah memperpanjang sewa di Urban Kost ";

                (new WhatsAppService())->send($renewal->user->phone, $waMsg);
            } catch (\Throwable $e) {
                Log::warning('[InvoiceController] WA renewal verified (tenant): ' . $e->getMessage());
            }
        }

        $admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->where('is_active', true)->get();
        foreach ($admins as $admin) {
            InAppNotification::send(
                $admin->id,
                'renewal_completed',
                ' Perpanjangan Selesai — ' . $roomNumber,
                $userName . ' telah membayar perpanjangan kamar ' . $roomNumber .
                    ' selama ' . $durasi . ' bulan (hingga ' . $newEndFmt . ')' .
                    '. Total: ' . $totalFmt . '.',
                route('admin.renewals.show', $renewal->id)
            );

            if ($admin->phone) {
                try {
                    $waAdmin = " *Perpanjangan Sewa Selesai*\n\n"
                        . " *Detail:*\n"
                        . "• Penghuni     : *{$userName}*\n"
                        . "• Kamar        : *{$roomNumber}*\n"
                        . "• Durasi       : *{$durasi} bulan*\n"
                        . "• Sewa Hingga  : *{$newEndFmt}*\n"
                        . "• Total Bayar  : *{$totalFmt}*\n"
                        . "• Verifikasi   : " . now()->format('d M Y') . " \n\n"
                        . "Perpanjangan sewa berhasil diverifikasi dan sewa penghuni telah diperbarui.";

                    (new WhatsAppService())->send($admin->phone, $waAdmin);
                } catch (\Throwable $e) {
                    Log::warning('[InvoiceController] WA renewal verified (admin ' . $admin->id . '): ' . $e->getMessage());
                }
            }
        }
    }

    private function handleRegularInvoiceVerified(Invoice $invoice, Payment $payment, NotificationService $notif): \Illuminate\Http\RedirectResponse
    {
        $user     = $invoice->user;
        $upgraded = false;

        $roomStillOccupied = Booking::where('room_id', $invoice->room_id)
            ->whereIn('status', ['approved', 'active'])
            ->where('user_id', '!=', $invoice->user_id)
            ->exists();

        if ($user && $user->role?->name === 'prospective_tenant'
            && !in_array($invoice->invoice_type, ['waiting_list_dp', 'waiting_list_remaining'])) {

            if (!$roomStillOccupied) {
                $tenantRole = Role::where('name', 'tenant')->firstOrFail();
                $user->update(['role_id' => $tenantRole->id, 'is_active' => true]);
                LoginController::generateAndSendOtp($user->email, 'login', $user->name);

                try {
                    $notif->notifyTenantUpgraded($user, $invoice->room);
                } catch (\Exception $e) {
                    Log::warning('[InvoiceController] notifyTenantUpgraded gagal: ' . $e->getMessage());
                }

                $upgraded = true;
            } else {
                InAppNotification::send(
                    $invoice->user_id,
                    'payment_verified_waiting_room',
                    ' Pembayaran Diterima — Menunggu Kamar Tersedia',
                    'Pembayaran invoice ' . $invoice->invoice_number .
                        ' telah diverifikasi. Anda akan diaktifkan setelah kamar tersedia.',
                    route('prospective.invoices.show', $invoice->id),
                    ['invoice_id' => $invoice->id]
                );
            }
        }

        if (!$roomStillOccupied) {
            $invoice->room?->update(['status' => 'occupied']);
            if ($invoice->booking && $invoice->booking->status === 'approved') {
                $invoice->booking->update(['status' => 'active']);
            }
        }

        $roleMap   = [
            'tenant'             => 'tenant.invoices.show',
            'prospective_tenant' => 'prospective.invoices.show',
        ];
        $roleName  = $user?->role?->name ?? 'tenant';
        $showRoute = $roleMap[$roleName] ?? 'tenant.invoices.show';

        InAppNotification::send(
            $invoice->user_id,
            'payment_verified',
            ' Pembayaran Terverifikasi — Lunas!',
            'Pembayaran invoice ' . $invoice->invoice_number .
                ' sebesar ' . $payment->formatted_amount . ' telah diverifikasi. Terima kasih!',
            route($showRoute, $invoice->id),
            ['payment_id' => $payment->id, 'invoice_id' => $invoice->id]
        );

        try {
            $notif->notifyInvoicePaid($invoice);
        } catch (\Exception $e) {
            Log::warning('[InvoiceController] notifyInvoicePaid gagal: ' . $e->getMessage());
        }

        $msg = ' Pembayaran <strong>' . $invoice->invoice_number . '</strong> berhasil diverifikasi.';

        if ($roomStillOccupied) {
            $msg .= ' Kamar <strong>' . ($invoice->room?->room_number ?? '-') . '</strong> masih dihuni.';
        } else {
            $msg .= ' Kamar <strong>' . ($invoice->room?->room_number ?? '-') . '</strong> → Occupied.';
        }

        if ($upgraded) {
            $msg .= ' <strong>' . $user->name . '</strong> diupgrade menjadi Penghuni Aktif.';
        }

        return back()->with('success', $msg);
    }

    private function lookupWaitingList(Invoice $invoice): ?WaitingList
    {
        if ($invoice->waiting_list_id) {
            $wl = WaitingList::find($invoice->waiting_list_id);
            if ($wl) return $wl;
        }

        if ($invoice->booking_id) {
            $wl = WaitingList::where('booking_id', $invoice->booking_id)
                ->whereNotIn('status', ['cancelled', 'expired'])
                ->latest()
                ->first();
            if ($wl) return $wl;
        }

        if ($invoice->user_id && $invoice->room_id
            && in_array($invoice->invoice_type, ['waiting_list_dp', 'waiting_list_remaining'])) {
            $wl = WaitingList::where('user_id', $invoice->user_id)
                ->where('room_id', $invoice->room_id)
                ->whereNotIn('status', ['cancelled', 'expired'])
                ->latest()
                ->first();
            if ($wl) return $wl;
        }

        return null;
    }
}
