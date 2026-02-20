<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    public function invoice($id)
    {
        $booking = Booking::findOrFail($id);

        // Optional: check if the booking belongs to the authenticated customer
        if ($booking->customer_id !== auth()->id()) {
            abort(403);
        }

        return view('customer.booking.invoice', compact('booking'));
    }
}
