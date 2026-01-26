<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Booking;

class BookingController extends Controller
{
    public function active()
    {
        $bookings = Booking::where('status','active')->paginate(25);
        return view('admin.bookings.active', compact('bookings'));
    }

    public function expired()
    {
        $bookings = Booking::where('status','expired')->paginate(25);
        return view('admin.bookings.expired', compact('bookings'));
    }
}
