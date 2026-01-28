<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingPaidMail;
use App\Models\Notification;
use App\Models\User;

class WarehouseController extends Controller
{
    // Book page
    public function book($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        return view('customer.warehouses.book', compact('warehouse'));
    }

    // Calculate total
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

    // Agreement Page
    public function agreement(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);

        return view('customer.warehouses.agreement', [
            'warehouse' => $warehouse,
            'data' => $request->all()
        ]);
    }

    // Final Confirm Booking → Auto Confirm + Notifications
    public function finalConfirm(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $customer = auth()->user();

        $booking = Booking::create([
            'warehouse_id' => $warehouse->id,
            'customer_id' => $customer->id,
            'area' => $request->area,
            'items' => $request->items,
            'items_detail' => $request->items_detail,
            'months' => $request->months,
            'total_price' => $request->total_price,
            'status' => 'confirmed',
        ]);

        // -------------------
        // Notifications
        // -------------------
        // Admin
        Notification::create([
            'user_id' => User::where('role','admin')->first()->id,
            'type' => 'booking',
            'message' => "New booking from {$customer->name} for {$warehouse->name}",
        ]);

        // Owner
        Notification::create([
            'user_id' => $warehouse->owner_id,
            'type' => 'booking',
            'message' => "New booking for your warehouse {$warehouse->name} by {$customer->name}",
        ]);

        // Email to Customer
        Mail::raw(
            "Your warehouse booking is successful.\nTotal Price: {$booking->total_price}",
            function ($message) use ($customer) {
                $message->to($customer->email)
                        ->subject('Warehouse Booking Confirmation');
            }
        );

        return redirect()->route('customer.payment', $booking->id)
                         ->with('success','Warehouse booked successfully.');
    }

    // Optional: Customer Booking Index
    public function index()
    {
        $bookings = Booking::where('customer_id',auth()->id())
            ->with('warehouse')
            ->latest()
            ->get();

        return view('customer.bookings.index', compact('bookings'));
    }
}
