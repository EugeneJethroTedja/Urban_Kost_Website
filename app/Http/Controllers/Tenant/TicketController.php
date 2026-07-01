<?php

namespace App\Http\Controllers\Tenant;

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
        $userId = auth()->id();

        $runningStatuses  = ['open', 'in_progress'];
        $finishedStatuses = ['resolved', 'closed'];

        $query = Ticket::where('user_id', $userId);

        if ($request->status === 'running') {
            $query->whereIn('status', $runningStatuses);
        } elseif ($request->status === 'finished') {
            $query->whereIn('status', $finishedStatuses);
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $stats = [
            'running'  => Ticket::where('user_id', $userId)->whereIn('status', $runningStatuses)->count(),
            'finished' => Ticket::where('user_id', $userId)->whereIn('status', $finishedStatuses)->count(),
            'total'    => Ticket::where('user_id', $userId)->count(),
        ];

        return view('tenant.tickets.index', compact('tickets', 'stats'));
    }

    public function create()
    {
        return view('tenant.tickets.create');
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'subject'     => 'required|string|max:200',
            'category'    => 'required|in:maintenance,payment,facility,complaint,other',
            'priority'    => 'required|in:low,normal,high,urgent',
            'description' => 'required|string|min:10',
            'attachment'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072',
        ], [
            'subject.required'     => 'Subjek wajib diisi.',
            'description.required' => 'Deskripsi wajib diisi.',
            'description.min'      => 'Deskripsi minimal 10 karakter.',
        ]);

        $attachPath = null;
        if ($request->hasFile('attachment')) {
            $attachPath = $request->file('attachment')->store('ticket-attachments', 'public');
        }

        $ticket = Ticket::create([
            'user_id'     => auth()->id(),
            'subject'     => $request->subject,
            'category'    => $request->category,
            'priority'    => $request->priority,
            'description' => $request->description,
            'attachment'  => $attachPath,
            'status'      => 'open',
        ]);

        app(\App\Services\NotificationService::class)->notifyNewTicket($ticket);

        return redirect()->route('tenant.tickets.show', $ticket->id)
            ->with('success', ' Tiket berhasil dibuat! Nomor tiket Anda: <strong>' . $ticket->ticket_number . '</strong>');
    }

    public function show($id)
    {
        $ticket  = Ticket::with(['replies.user'])->where('user_id', auth()->id())->findOrFail($id);
        $replies = $ticket->replies()->with('user')->orderBy('created_at')->get();
        return view('tenant.tickets.show', compact('ticket', 'replies'));
    }

    public function reply(Request $request, $id)
    {
        $ticket = Ticket::where('user_id', auth()->id())->findOrFail($id);
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
            ->notifyTicketRepliedByTenant($ticket, $request->message);

        return back()->with('success', 'Balasan terkirim.');
    }

    public function close($id)
    {
        $ticket = Ticket::where('user_id', auth()->id())->findOrFail($id);
        $ticket->update(['status' => 'closed']);

        app(\App\Services\NotificationService::class)
            ->notifyTicketClosedByTenant($ticket);

        return back()->with('success', 'Tiket ditutup.');
    }

    public function rate(Request $request, $id)
    {
        $request->validate(['rating' => 'required|integer|min:1|max:5']);
        $ticket = Ticket::where('user_id', auth()->id())
            ->whereIn('status', ['resolved', 'closed'])
            ->findOrFail($id);
        $ticket->update(['rating' => $request->rating]);
        return back()->with('success', 'Terima kasih atas penilaian Anda!');
    }

    public function edit($id)
    {
        $ticket = Ticket::where('user_id', auth()->id())
            ->whereIn('status', ['open', 'in_progress'])
            ->findOrFail($id);

        return view('tenant.tickets.edit', compact('ticket'));
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::where('user_id', auth()->id())
            ->whereIn('status', ['open', 'in_progress'])
            ->findOrFail($id);

        $request->validate([
            'subject'     => 'required|string|max:200',
            'category'    => 'required|in:maintenance,payment,facility,complaint,other',
            'priority'    => 'required|in:low,normal,high,urgent',
            'description' => 'required|string|min:10',
            'attachment'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072',
        ], [
            'subject.required'     => 'Subjek wajib diisi.',
            'description.required' => 'Deskripsi wajib diisi.',
            'description.min'      => 'Deskripsi minimal 10 karakter.',
        ]);

        $attachPath = $ticket->attachment;
        if ($request->hasFile('attachment')) {
            if ($attachPath) {
                \Storage::disk('public')->delete($attachPath);
            }
            $attachPath = $request->file('attachment')->store('ticket-attachments', 'public');
        }

        $ticket->update([
            'subject'     => $request->subject,
            'category'    => $request->category,
            'priority'    => $request->priority,
            'description' => $request->description,
            'attachment'  => $attachPath,
        ]);

        return redirect()->route('tenant.tickets.show', $ticket->id)
            ->with('success', ' Tiket berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $ticket = Ticket::where('user_id', auth()->id())
            ->whereIn('status', ['open', 'in_progress'])
            ->findOrFail($id);

        if ($ticket->attachment) {
            \Storage::disk('public')->delete($ticket->attachment);
        }

        $ticket->delete();

        return redirect()->route('tenant.tickets.index')
            ->with('success', ' Tiket berhasil dihapus.');
    }
}
