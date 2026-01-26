<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingStatusChanged;


class OwnerBookingController extends Controller
{
    public function index()
    {
        // Get bookings for warehouses owned by this owner
        $bookings = Booking::whereHas('warehouse', function($q){
            $q->where('owner_id', Auth::id());
        })
        ->with(['warehouse', 'customer']) // note: 'customer' not 'user'
        ->get();

        return view('owner.bookings.index', compact('bookings'));
    }
    public function approve(Booking $booking)
{
    // Only approve if this warehouse belongs to this owner
    if ($booking->warehouse->owner_id != Auth::id()) {
        abort(403);
    }

    $booking->status = 'approved';
    $booking->save();

    
    // Send email to customer
    Mail::to($booking->customer->email)->send(new BookingStatusChanged($booking, 'approved'));

    return redirect()->back()->with('success', 'Booking approved.');
}

public function cancel(Booking $booking)
{
    if ($booking->warehouse->owner_id != Auth::id()) {
        abort(403);
    }

    $booking->status = 'cancelled';
    $booking->save();

    // Send email to customer
    Mail::to($booking->customer->email)->send(new BookingStatusChanged($booking, 'cancelled'));

    return redirect()->back()->with('success', 'Booking cancelled.');
}

}
