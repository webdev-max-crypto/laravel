<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Warehouse;

class BookingController extends Controller
{
    // Show booking form
    public function create(Warehouse $warehouse)
    {
        return view('customer.booking.create', compact('warehouse'));
    }

    // Store booking
    public function store(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'start_date'=>'required|date',
            'end_date'=>'required|date|after_or_equal:start_date'
        ]);

        $days = (strtotime($request->end_date) - strtotime($request->start_date))/(60*60*24) + 1;
        $total_price = $days * $warehouse->price_per_unit;

        Booking::create([
            'warehouse_id'=>$warehouse->id,
            'customer_id'=>Auth::id(),
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
            'total_price'=>$total_price,
            'status'=>'active'
        ]);

        return redirect()->route('customer.dashboard')->with('success','Warehouse booked!');
    }
}
