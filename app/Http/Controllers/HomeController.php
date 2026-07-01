<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Renewal;
use App\Models\Room;
use App\Models\User;
use App\Models\WaitingList;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $this->healRoomStatuses();

        $rooms          = Room::with('images')->orderBy('price')->get();
        $user           = auth()->user();
        $roomData       = $rooms->map(fn($room) => $this->buildRoomData($room));
        $availableCount = Room::where('status', 'available')->count();
        $totalRooms     = Room::count();

        $userActiveBooking = null;
        $userActiveRoom    = null;
        if ($user) {
            $userActiveBooking = Booking::where('user_id', $user->id)
                ->whereIn('status', ['approved', 'active'])
                ->with('room')
                ->latest()
                ->first();
            $userActiveRoom = $userActiveBooking?->room;
        }

        return view('home.index', compact(
            'rooms', 'roomData', 'availableCount', 'totalRooms',
            'userActiveBooking', 'userActiveRoom', 'user'
        ));
    }

    public function showRoom(Room $room)
    {
        $room->load('images');
        $similarRooms = Room::with('images')->where('id', '!=', $room->id)->orderBy('price')->limit(3)->get();
        $waitingCount = WaitingList::where('room_id', $room->id)
            ->whereIn('status', ['waiting', 'notified'])->count();
        $data = $this->buildRoomData($room);

        return view('home.room-show', compact('room', 'similarRooms', 'waitingCount') + [
            'activeTenant'  => $data['active_tenant'],
            'tenantEndDate' => $data['tenant_end_date'],
            'activeWL'      => $data['active_wl'],
            'nextTenant'    => $data['next_tenant'],
            'nextStartDate' => $data['next_start_date'],
        ]);
    }

    public function prospectiveDashboard()
    {
        $user = auth()->user();

        $bookings = Booking::where('user_id', $user->id)
            ->whereNotIn('status', ['rejected', 'cancelled'])
            ->with(['room'])->orderBy('created_at', 'desc')->get();

        $latestBooking     = $bookings->first();
        $activeWaitingList = WaitingList::where('user_id', $user->id)
            ->with(['room', 'booking'])
            ->whereIn('status', ['waiting', 'notified'])
            ->orderBy('created_at', 'desc')->first();

        $dpInvoice        = null;
        $dpIsPaid         = false;
        $remainingInvoice = null;
        $activeInvoice    = null;

        if ($activeWaitingList) {
            $dpInvoice = Invoice::where('invoice_type', 'waiting_list_dp')
                ->where(function ($q) use ($activeWaitingList) {
                    $q->where('waiting_list_id', $activeWaitingList->id)
                      ->orWhere('booking_id', $activeWaitingList->booking_id)
                      ->orWhere(fn($q2) => $q2
                          ->where('user_id', $activeWaitingList->user_id)
                          ->where('room_id', $activeWaitingList->room_id));
                })->latest()->first();

            $dpIsPaid = $dpInvoice && $dpInvoice->status === 'paid';

            $remainingInvoice = Invoice::where('waiting_list_id', $activeWaitingList->id)
                ->where('invoice_type', 'waiting_list_remaining')->latest()->first();

            if ($remainingInvoice && in_array($remainingInvoice->status, ['unpaid', 'overdue', 'pending_verification'])) {
                $activeInvoice = $remainingInvoice;
            } elseif ($dpInvoice && in_array($dpInvoice->status, ['unpaid', 'overdue', 'pending_verification'])) {
                $activeInvoice = $dpInvoice;
            }
        }

        $allInvoices     = Invoice::with(['room'])->where('user_id', $user->id)
                            ->whereNotIn('status', ['cancelled'])
                            ->orderBy('created_at', 'desc')->limit(5)->get();
        $unpaidInvoices  = Invoice::where('user_id', $user->id)
                            ->whereNotIn('status', ['cancelled'])
                            ->whereIn('status', ['unpaid', 'overdue'])
                            ->orderBy('due_date', 'asc')->get();
        $rooms           = Room::where('status', 'available')->get();
        $pendingInvoices = Invoice::where('user_id', $user->id)->whereIn('status', ['unpaid', 'pending_verification'])->with('room')->get();

        $stats = [
            'has_active_wl'  => $activeWaitingList !== null,
            'dp_is_paid'     => $dpIsPaid,
            'unpaid_count'   => $unpaidInvoices->count(),
            'unpaid_total'   => $unpaidInvoices->sum('total_amount'),
            'booking_status' => $latestBooking?->status ?? 'none',
            'wl_status'      => $activeWaitingList?->status ?? 'none',
        ];

        return view('home.prospective-dashboard', compact(
            'user', 'bookings', 'latestBooking', 'activeWaitingList',
            'dpInvoice', 'dpIsPaid', 'remainingInvoice', 'activeInvoice',
            'allInvoices', 'unpaidInvoices', 'rooms', 'pendingInvoices', 'stats'
        ));
    }

    public function tenantDashboard()
    {
        $user           = auth()->user();
        $activeBooking  = Booking::with(['room'])->where('user_id', $user->id)
                            ->whereIn('status', ['approved', 'active'])->latest()->first();
        $recentInvoices = Invoice::with(['room'])->where('user_id', $user->id)
                            ->whereNotIn('status', ['cancelled'])
                            ->orderBy('created_at', 'desc')->limit(5)->get();
        $unpaidInvoices = Invoice::where('user_id', $user->id)
                            ->whereNotIn('status', ['cancelled'])
                            ->whereIn('status', ['unpaid', 'overdue'])
                            ->orderBy('due_date', 'asc')->get();
        $nextDueInvoice = $unpaidInvoices->first();
        $activeRenewal  = Renewal::with(['room'])->where('user_id', $user->id)
                            ->whereNotIn('status', ['rejected', 'cancelled'])->latest()->first();
        $currentRoom    = $activeBooking?->room;

        $stats = [
            'unpaid_count' => $unpaidInvoices->count(),
            'unpaid_total' => $unpaidInvoices->sum('total_amount'),
            'paid_count'   => Invoice::where('user_id', $user->id)
                                ->whereNotIn('status', ['cancelled'])
                                ->where('status', 'paid')->count(),
        ];

        return view('tenant.dashboard', compact(
            'user', 'activeBooking', 'currentRoom', 'recentInvoices',
            'unpaidInvoices', 'nextDueInvoice', 'activeRenewal', 'stats'
        ));
    }

    public function adminDashboard()
    {
        $stats = [
            'total_rooms'        => Room::count(),
            'available_rooms'    => Room::where('status', 'available')->count(),
            'occupied_rooms'     => Room::where('status', 'occupied')->count(),
            'maintenance_rooms'  => Room::where('status', 'maintenance')->count(),
            'total_tenants'      => User::whereHas('role', fn($q) => $q->where('name', 'tenant'))->where('is_active', true)->count(),
            'total_prospective'  => User::whereHas('role', fn($q) => $q->where('name', 'prospective_tenant'))->where('is_active', true)->count(),
            'pending_bookings'   => Booking::where('status', 'pending')->count(),
            'active_bookings'    => Booking::whereIn('status', ['approved', 'active'])->count(),
            'waiting_list'       => WaitingList::whereIn('status', ['waiting', 'notified'])->count(),
            'pending_payments'   => Invoice::where('status', 'pending_verification')
                                        ->whereNotIn('status', ['cancelled'])->count(),
            'unpaid_invoices'    => Invoice::whereIn('status', ['unpaid', 'overdue'])
                                        ->whereNotIn('status', ['cancelled'])->count(),
            'revenue_this_month' => Invoice::where('status', 'paid')
                                        ->whereMonth('paid_at', now()->month)
                                        ->whereYear('paid_at', now()->year)
                                        ->sum('total_amount'),
            'open_tickets'       => class_exists(\App\Models\Ticket::class)
                                        ? \App\Models\Ticket::whereIn('status', ['open', 'in_progress'])->count() : 0,
            'urgent_tickets'     => class_exists(\App\Models\Ticket::class)
                                        ? \App\Models\Ticket::where('priority', 'urgent')->whereIn('status', ['open', 'in_progress'])->count() : 0,
        ];

        $recentBookings     = Booking::with(['user', 'room'])->orderBy('created_at', 'desc')->limit(5)->get();
        $pendingInvoices    = Invoice::with(['user', 'room'])->where('status', 'pending_verification')->orderBy('created_at', 'desc')->limit(5)->get();
        $activeWaitingLists = WaitingList::with(['user', 'room', 'booking'])->whereIn('status', ['waiting', 'notified'])->orderBy('created_at', 'asc')->limit(5)->get();

        return view('admin.dashboard', compact(
            'stats', 'recentBookings', 'pendingInvoices', 'activeWaitingLists'
        ));
    }

    private function buildRoomData(Room $room): array
    {
        $activeBooking = Booking::where('room_id', $room->id)
            ->whereIn('status', ['approved', 'active'])
            ->with('user')
            ->latest()
            ->first();

        $activeTenant  = $activeBooking?->user;
        $tenantEndDate = null;

        if ($activeBooking && $activeBooking->check_in_date && $activeBooking->duration_months) {
            $tenantEndDate = Carbon::parse($activeBooking->check_in_date)
                ->addMonths($activeBooking->duration_months);
        }
        
        $activeWL = Booking::where('room_id', $room->id)
            ->where('status', 'waiting_list')
            ->latest()
            ->first();

        return [
            'room'            => $room,
            'active_tenant'   => $activeTenant,
            'tenant_end_date' => $tenantEndDate,
            'next_tenant'     => null,
            'next_start_date' => null,
            'active_wl'       => $activeWL,
        ];
    }

    private function healRoomStatuses(): void
    {
        $today = Carbon::today();

        WaitingList::whereIn('status', ['waiting', 'notified'])
            ->get()
            ->each(function ($wl) use ($today) {
                $invoiceAktif = Invoice::where('room_id', $wl->room_id)
                    ->where('user_id', $wl->user_id)
                    ->where('status', 'paid')
                    ->where('period_start', '<=', $today)
                    ->where('period_end', '>=', $today)
                    ->exists();

                if ($invoiceAktif) {
                    $wl->update(['status' => 'converted']);
                }
            });

        WaitingList::whereIn('status', ['waiting', 'notified'])
            ->whereHas('booking', fn($q) => $q
                ->whereIn('status', ['rejected', 'cancelled'])
            )
            ->update(['status' => 'cancelled']);

        WaitingList::whereIn('status', ['waiting', 'notified'])
            ->where(function ($q) {
                $q->whereNull('booking_id')
                ->orWhereDoesntHave('booking');
            })
            ->update(['status' => 'cancelled']);

        $expiredRooms = Room::whereIn('status', ['occupied', 'reserved'])
            ->whereDoesntHave('invoices', fn($q) => $q
                ->where('status', 'paid')
                ->whereNotNull('period_end')
                ->where('period_end', '>=', $today)
                ->whereNotNull('period_start')
                ->where('period_start', '<=', $today)
            )->get();

        foreach ($expiredRooms as $room) {
            $hasActive = Invoice::where('room_id', $room->id)
                ->where('status', 'paid')
                ->where('period_end', '>=', $today)
                ->where('period_start', '<=', $today)
                ->exists();

            if ($hasActive) continue;

            $room->update(['status' => 'available']);

            $nextInvoice = Invoice::where('room_id', $room->id)
                ->where('status', 'paid')
                ->whereNotNull('period_start')
                ->where('period_start', '>=', $today)
                ->orderBy('period_start', 'asc')
                ->first();

            if ($nextInvoice) {
                $room->update(['status' => 'reserved']);
                WaitingList::where('room_id', $room->id)
                    ->where('user_id', $nextInvoice->user_id)
                    ->where('status', 'waiting')
                    ->update(['status' => 'notified']);
            } else {
                $nextWL = WaitingList::where('room_id', $room->id)
                    ->where('status', 'waiting')
                    ->orderBy('created_at')->first();

                if ($nextWL) {
                    $nextWL->update(['status' => 'notified']);
                    $room->update(['status' => 'reserved']);
                }
            }
        }

        Room::where('status', 'available')
            ->whereHas('invoices', fn($q) => $q
                ->where('status', 'paid')
                ->where('period_end', '>=', $today)
                ->where('period_start', '<=', $today)
            )->update(['status' => 'occupied']);

        $roomsToCheck = Room::whereIn('status', ['reserved', 'occupied'])->get();
        foreach ($roomsToCheck as $room) {
            $hasQueue = Invoice::where('room_id', $room->id)
                ->where('status', 'paid')
                ->whereNotNull('period_start')
                ->where('period_start', '>', $today)
                ->exists();

            $hasWL = WaitingList::where('room_id', $room->id)
                ->whereIn('status', ['waiting', 'notified'])
                ->exists();

            $hasCurrentTenant = Invoice::where('room_id', $room->id)
                ->where('status', 'paid')
                ->where('period_start', '<=', $today)
                ->where('period_end', '>=', $today)
                ->exists();

            if (!$hasQueue && !$hasWL) {
                if ($hasCurrentTenant) {
                    $room->update(['status' => 'occupied']);
                } else {
                    $room->update(['status' => 'available']);
                }
            }
        }
    }
}
