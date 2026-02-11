<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingStatusChanged;
use App\Models\Notification;

class OwnerBookingController extends Controller
{
    // Owner dashboard - show all bookings for this owner's warehouses
    public function index()
    {
        $owner = Auth::user();

        // Fetch bookings for warehouses owned by this owner
        $bookings = Booking::whereIn('warehouse_id', $owner->warehouses->pluck('id'))
            ->with(['warehouse', 'customer']) // eager load
            ->orderBy('created_at', 'desc')
            ->get();

        return view('owner.bookings.index', compact('bookings'));
    }

    // Approve a booking
    public function approve(Booking $booking)
    {
        if ($booking->warehouse->owner_id != Auth::id()) {
            abort(403);
        }

        $booking->status = 'approved';
        $booking->save();

        // Notify customer
        Notification::create([
            'user_id' => $booking->customer_id,
            'message' => "Your booking #{$booking->id} for warehouse {$booking->warehouse->name} has been approved by the owner.",
        ]);

        // Notify owner (optional, for logs)
        Notification::create([
            'user_id' => Auth::id(),
            'message' => "You approved booking #{$booking->id}. Payment: {$booking->payment_method}, Status: {$booking->payment_status}",
        ]);

        // Send email to customer
        Mail::to($booking->customer->email)->send(new BookingStatusChanged($booking, 'approved'));

        return redirect()->back()->with('success', 'Booking approved.');
    }

    // Cancel a booking
    public function cancel(Booking $booking)
    {
        if ($booking->warehouse->owner_id != Auth::id()) {
            abort(403);
        }

        $booking->status = 'cancelled';
        $booking->save();

        // Notify customer
        Notification::create([
            'user_id' => $booking->customer_id,
            'message' => "Your booking #{$booking->id} for warehouse {$booking->warehouse->name} has been cancelled by the owner.",
        ]);

        // Notify owner (optional)
        Notification::create([
            'user_id' => Auth::id(),
            'message' => "You cancelled booking #{$booking->id}. Payment: {$booking->payment_method}, Status: {$booking->payment_status}",
        ]);

        // Send email to customer
        Mail::to($booking->customer->email)->send(new BookingStatusChanged($booking, 'cancelled'));

        return redirect()->back()->with('success', 'Booking cancelled.');
    }
}
