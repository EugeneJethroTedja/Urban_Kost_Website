<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InAppNotification;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['user'])
            ->orderByRaw("FIELD(priority,'urgent','high','normal','low')")
            ->orderBy('created_at', 'asc');

        if ($request->status)   $query->where('status', $request->status);
        if ($request->priority) $query->where('priority', $request->priority);

        $tickets = $query->paginate(15);

        $stats = [
            'open'     => Ticket::whereIn('status', ['open'])->count(),
            'urgent'   => Ticket::where('priority', 'urgent')->whereIn('status', ['open','in_progress'])->count(),
            'pending'  => Ticket::where('status', 'in_progress')->count(),
            'resolved' => Ticket::where('status', 'resolved')->whereDate('resolved_at', today())->count(),
        ];

        return view('admin.tickets.index', compact('tickets', 'stats'));
    }

    public function show($id)
    {
        $ticket  = Ticket::with(['user', 'replies.user', 'assignee'])->findOrFail($id);
        $admins  = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->get();
        $replies = $ticket->replies()->with('user')->orderBy('created_at')->get();
        return view('admin.tickets.show', compact('ticket', 'admins', 'replies'));
    }

    public function reply(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $request->validate(['message' => 'required|string|min:2']);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id'   => auth()->id(),
            'message'   => $request->message,
        ]);

        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        app(\App\Services\NotificationService::class)
            ->notifyTicketRepliedByAdmin($ticket, $request->message);

        return back()->with('success', 'Balasan terkirim ke penghuni.');
    }

    public function resolve(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $note   = $request->resolution_note ?? 'Masalah telah diselesaikan oleh tim admin.';

        $ticket->update([
            'status'      => 'resolved',
            'resolved_at' => now(),
        ]);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id'   => auth()->id(),
            'message'   => $note,
        ]);

        app(\App\Services\NotificationService::class)
            ->notifyTicketResolved($ticket);

        return back()->with('success', ' Tiket berhasil diselesaikan. MTTR: ' . $ticket->mttr_hours . ' jam');
    }

    public function assign(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $request->validate(['assigned_to' => 'required|exists:users,id']);
        $ticket->update([
            'assigned_to' => $request->assigned_to,
            'status'      => 'in_progress',
        ]);
        return back()->with('success', 'Tiket berhasil di-assign.');
    }
}
