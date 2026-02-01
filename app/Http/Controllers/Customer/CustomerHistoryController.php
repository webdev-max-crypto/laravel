<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking; // Booking model
use App\Models\Warehouse; // Warehouse model (relation ke liye)
use Illuminate\Support\Facades\Auth;

class CustomerHistoryController extends Controller
{
    /**
     * Show all bookings of the logged-in customer
     */
    public function index()
    {
        // Customer ki bookings, newest pehle
        $bookings = Booking::with('warehouse')
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->get();

        // Pass data to view
        return view('customer.history.index', compact('bookings'));
    }
}
