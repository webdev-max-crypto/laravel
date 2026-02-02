<?php

namespace App\Helpers;

use App\Models\Notification;
use Illuminate\Support\Facades\Mail;

class Notify
{
    // Send in-app notification
    public static function send($userId, $type, $message)
    {
        Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'message' => $message,
            'is_read' => 0
        ]);
    }

    // Send email notification
    public static function email($userEmail, $subject, $body)
    {
        Mail::raw($body, function ($message) use ($userEmail, $subject) {
            $message->to($userEmail)
                    ->subject($subject);
        });
    }
}
