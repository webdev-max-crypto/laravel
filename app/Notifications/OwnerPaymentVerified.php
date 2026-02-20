<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OwnerPaymentVerified extends Notification
{
    use Queueable;

    public $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['database']; // only database notifications
    }

    public function toDatabase($notifiable)
    {
        // Directly match your table columns
        return [
            'user_id' => $notifiable->id,
            'message' => "Order #{$this->booking->id} payment verified",
            'data' => json_encode([
                'title' => 'New Payment Received',
                'message' => "Order #{$this->booking->id} payment verified",
                'order_id' => $this->booking->id
            ]),
            'is_read' => 0
        ];
    }
}