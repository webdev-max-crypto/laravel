<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    // List all notifications for admin
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('customer.notifications.index', compact('notifications'));
    }

    // Mark a notification as read
    public function markAsRead($id)
    {
        $notif = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notif->update(['is_read' => true]);

        return redirect()->back();
    }
}
