<?php

namespace App\Http\Controllers;

use App\Models\InAppNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = InAppNotification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        InAppNotification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return view('shared.notifications', compact('notifications'));
    }

    public function unreadCount()
    {
        $count = InAppNotification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function markRead($id)
    {
        InAppNotification::where('user_id', auth()->id())
            ->where('id', $id)
            ->update(['is_read' => true, 'read_at' => now()]);

        return redirect()->back();
    }
}
