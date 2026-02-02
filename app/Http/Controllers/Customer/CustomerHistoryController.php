<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class CustomerHistoryController extends Controller
{
    public function index()
    {
        $customer = auth()->user();
        $bookings = Booking::where('customer_id', $customer->id)
                            ->orderBy('created_at','desc')
                            ->with('warehouse')
                            ->get();

        return view('customer.history.index', compact('bookings'));
    }
}
