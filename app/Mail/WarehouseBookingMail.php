<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class WarehouseBookingMail extends Mailable
{
    public $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        return $this->subject('Warehouse Booking Confirmation')
                    ->view('emails.booking');
    }
}