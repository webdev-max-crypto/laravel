<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;

class BookingStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $status;

    public function __construct(Booking $booking, $status)
    {
        $this->booking = $booking;
        $this->status = $status;
    }

    public function build()
    {
        return $this->subject("Your booking status has changed")
                    ->view('emails.booking_status_changed');
    }
}
