<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OwnerPaymentVerified extends Notification
{
    use Queueable;

    public function __construct(public $order) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'New Payment Received',
            'message' => 'Order #' . $this->order->id . ' payment verified',
            'order_id' => $this->order->id
        ];
    }
}
