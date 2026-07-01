<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Ticket;
use App\Models\Renewal;

class TenantController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $validInvoice = fn($q) => $q->where('user_id', $user->id)
            ->where(function ($q) {
                $q->whereNull('booking_id')
                ->orWhereHas('booking', fn($q) =>
                    $q->whereNotIn('status', ['rejected', 'cancelled'])
                );
            });

        $invoiceStats = [
            'unpaid'  => $validInvoice(Invoice::query())->whereIn('status', ['unpaid', 'overdue'])->count(),
            'pending' => $validInvoice(Invoice::query())->where('status', 'pending_verification')->count(),
            'total'   => $validInvoice(Invoice::query())->count(),
        ];

        $latestUnpaidInvoice = $validInvoice(Invoice::query())
            ->whereIn('status', ['unpaid', 'overdue'])
            ->orderBy('due_date')
            ->first();

        $ticketStats = [
            'open'     => Ticket::where('user_id', $user->id)->whereIn('status', ['open', 'in_progress'])->count(),
            'resolved' => Ticket::where('user_id', $user->id)->where('status', 'resolved')->count(),
        ];

        $bookings = Booking::where('user_id', $user->id)
            ->with('room')
            ->orderBy('created_at', 'desc')
            ->get();

        $renewalInquiry = null;

        $expiringBooking = Booking::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'active'])
            ->with('room')
            ->get()
            ->filter(function ($b) {
                $end = $b->computed_end_date;
                if (!$end) return false;
                $end = \Carbon\Carbon::parse($end)->startOfDay();
                $today = now()->startOfDay();
                $limit = now()->addDays(10)->startOfDay();
                return $end->gte($today) && $end->lte($limit);
            })
            ->sortBy(fn($b) => $b->computed_end_date)
            ->first();

        $daysUntilExpiry = $expiringBooking
            ? (int) now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($expiringBooking->computed_end_date)->startOfDay())
            : null;

        return view('tenant.dashboard', compact(
            'user', 'bookings', 'invoiceStats',
            'latestUnpaidInvoice', 'ticketStats', 'renewalInquiry',
            'expiringBooking', 'daysUntilExpiry'
        ));
    }

    public function myBookings()
    {
        $user = auth()->user();
        $statusFilter = request('status');

        $allBookings = Booking::where('user_id', $user->id)
            ->with('room')
            ->orderBy('created_at', 'desc')
            ->get();

        $bookingIds = $allBookings->pluck('id')->toArray();

        $paidInvMap = Invoice::where('user_id', $user->id)
            ->whereIn('booking_id', $bookingIds)
            ->where('status', 'paid')
            ->orderBy('period_end', 'desc')
            ->get()
            ->groupBy('booking_id');

        $statusMap     = [];
        $countPenghuni = $countPending = $countNonaktif = $countRejected = 0;

        foreach ($allBookings as $bk) {
            $inv = ($paidInvMap[$bk->id] ?? collect())->first();

            if ($bk->status === 'rejected') {
                $statusMap[$bk->id] = 'rejected';
                $countRejected++;
            } elseif ($inv?->period_end) {
                if ($inv->period_end->gte(now())) {
                    $statusMap[$bk->id] = 'penghuni';
                    $countPenghuni++;
                } else {
                    $statusMap[$bk->id] = 'nonaktif';
                    $countNonaktif++;
                }
            } elseif ($bk->status === 'pending') {
                $statusMap[$bk->id] = 'pending';
                $countPending++;
            } elseif ($bk->status === 'approved') {
                $statusMap[$bk->id] = 'calon';
                $countPending++;
            } else {
                $statusMap[$bk->id] = 'nonaktif';
                $countNonaktif++;
            }
        }

        $allCount = $allBookings->count();

        $filteredIds = collect($statusMap)
            ->when($statusFilter, fn($map) => $map->filter(fn($s) => $s === $statusFilter))
            ->keys()
            ->toArray();

        $bookings = Booking::where('user_id', $user->id)
            ->whereIn('id', $filteredIds)
            ->with('room')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $activeBooking = Booking::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'active'])
            ->latest()
            ->first();

        $activeRenewal = $activeBooking
            ? Renewal::where('user_id', $user->id)
                ->whereIn('status', ['pending_payment', 'pending_admin'])
                ->latest()
                ->first()
            : null;

        $unpaidInv = Invoice::where('user_id', $user->id)
            ->whereIn('status', ['unpaid', 'overdue'])
            ->count();

        return view('tenant.bookings', compact(
            'bookings',
            'activeBooking',
            'activeRenewal',
            'unpaidInv'
        ))
        ->with('tStatusMap',    $statusMap)
        ->with('paidInvMap',    $paidInvMap)
        ->with('allCount',      $allCount)
        ->with('countPenghuni', $countPenghuni)
        ->with('countPending',  $countPending)
        ->with('countNonaktif', $countNonaktif)
        ->with('countRejected', $countRejected);
    }
}
