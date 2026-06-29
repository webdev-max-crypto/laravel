<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /** List of users this role can chat with */
    public function inbox()
    {
        $me = auth()->user();

        // Get all users I have exchanged messages with
        $userIds = Message::where('sender_id', $me->id)
            ->orWhere('receiver_id', $me->id)
            ->get()
            ->map(fn($m) => $m->sender_id == $me->id ? $m->receiver_id : $m->sender_id)
            ->unique()
            ->values();

        $contacts = User::whereIn('id', $userIds)->get();

        // Allowed roles to start new chat with
        $allowedRoles = match($me->role) {
            'admin'    => ['owner'],
            'owner'    => ['admin', 'customer'],
            'customer' => ['owner'],
            default    => [],
        };

        $newContacts = User::whereIn('role', $allowedRoles)
            ->whereNotIn('id', $userIds)
            ->get();

        return view('chat.inbox', compact('contacts', 'newContacts', 'me'));
    }

    /** Show conversation with a specific user */
    public function show(User $user)
    {
        $me = auth()->user();

        // Mark messages from this user as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $me->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Message::where(function ($q) use ($me, $user) {
                $q->where('sender_id', $me->id)->where('receiver_id', $user->id);
            })->orWhere(function ($q) use ($me, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $me->id);
            })
            ->orderBy('created_at')
            ->get();

        return view('chat.show', compact('messages', 'user', 'me'));
    }

    /** Send a message */
    public function send(Request $request, User $user)
    {
        $request->validate(['body' => 'required|string|max:1000']);

        Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $user->id,
            'body'        => $request->body,
        ]);

        return redirect()->route('chat.show', $user->id);
    }

    /** Polling endpoint — returns new messages as JSON */
    public function poll(User $user, Request $request)
    {
        $me = auth()->user();
        $after = $request->query('after', 0);

        $messages = Message::where('sender_id', $user->id)
            ->where('receiver_id', $me->id)
            ->where('id', '>', $after)
            ->orderBy('created_at')
            ->get(['id', 'body', 'created_at']);

        // Mark as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $me->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    /** Unread count for navbar badge */
    public function unreadCount()
    
    {
        
        if (!auth()->check()) {
            return response()->json(['count' => 0]);
        }

        $count = Message::where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
