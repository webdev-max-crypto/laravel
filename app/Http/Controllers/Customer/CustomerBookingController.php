<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingPaidMail;
use App\Models\Notification;
use App\Models\User;

class CustomerBookingController extends Controller
{
    // Customer Payment Page
    public function payment(Booking $booking)
    {
        return view('customer.payment', compact('booking'));
    }

    // Store Payment Slip + Notifications
    public function paymentStore(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_slip' => 'required|image|max:4096'
        ]);

        $path = $request->file('payment_slip')->store('payments','public');

        $booking->update([
            'payment_slip' => $path
        ]);

        $customer = auth()->user();

        // -------------------
        // Notifications
        // -------------------
        // Admin
        Notification::create([
            'user_id' => User::where('role','admin')->first()->id,
            'type' => 'payment',
            'message' => "{$customer->name} submitted payment for {$booking->warehouse->name}",
        ]);

        // Owner
        Notification::create([
            'user_id' => $booking->warehouse->owner_id,
            'type' => 'payment',
            'message' => "Payment submitted for your warehouse {$booking->warehouse->name}",
        ]);

        // Send Email
        Mail::to($customer->email)->send(new BookingPaidMail($booking));

        return redirect()->route('customer.dashboard')
            ->with('success','Payment submitted successfully');
    }

    // Customer Booking History
    public function index()
    {
        $bookings = Booking::where('customer_id', auth()->id())
            ->with('warehouse')
            ->latest()
            ->get();

        return view('customer.bookings.index', compact('bookings'));
    }
}
