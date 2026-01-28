<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class WarehouseController extends Controller
{
    // 🟢 Book page
    public function book($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        return view('customer.warehouses.book', compact('warehouse'));
    }

    // 🟢 Calculate total price
    public function calculate(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);

        $data = $request->validate([
            'area' => 'required|integer|min:1',
            'items' => 'required|integer|min:1',
            'months' => 'required|integer|min:1',
        ]);

        $total = $warehouse->price_per_month * $data['months'];

        return view('customer.warehouses.summary', [
            'warehouse' => $warehouse,
            'data' => $data,
            'total' => $total
        ]);
    }

    // 🟢 Confirm booking
    public function confirm(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);

        $booking = Booking::create([
            'warehouse_id' => $warehouse->id,
            'customer_id' => auth()->id(),
            'area' => $request->area,
            'items' => $request->items,
            'months' => $request->months,
            'total_price' => $request->total_price,
            'status' => 'pending',
        ]);

        // 📧 Email Notification
        Mail::raw(
            "Your warehouse booking is successful.\nTotal Price: {$booking->total_price}",
            function ($message) {
                $message->to(auth()->user()->email)
                        ->subject('Warehouse Booking Confirmation');
            }
        );

        return redirect()
            ->route('customer.dashboard')
            ->with('success','Warehouse booked successfully.');
    }
}
