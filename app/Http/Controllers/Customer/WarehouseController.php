<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class WarehouseController extends Controller
{
    // Step 1: Show booking page
    public function book($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        return view('customer.warehouses.book', compact('warehouse'));
    }

    // Step 2: Calculate total & show summary
    public function calculate(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);

        $data = $request->validate([
            'area' => 'required|integer|min:1',
            'items' => 'required|integer|min:1',
            'months' => 'required|integer|min:1',
            'items_detail' => 'nullable|string',
        ]);

        $total = $warehouse->price_per_month * $data['months'];

        return view('customer.warehouses.summary', [
            'warehouse' => $warehouse,
            'data' => $data,
            'total' => $total
        ]);
    }

    // Step 3: Agreement page
    public function agreement(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);

        return view('customer.warehouses.agreement', [
            'warehouse' => $warehouse,
            'data' => $request->all()
        ]);
    }

    // Step 4: Final confirm booking
    public function finalConfirm(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $customer = auth()->user();

        $data = $request->validate([
            'area' => 'required|integer|min:1',
            'items' => 'required|integer|min:1',
            'months' => 'required|integer|min:1',
            'items_detail' => 'nullable|string',
            'total_price' => 'required|numeric',
        ]);

        // Create booking
        $booking = Booking::create([
            'warehouse_id' => $warehouse->id,
            'customer_id' => $customer->id,
            'user_id' => $customer->id,
            'area' => $data['area'],
            'items' => $data['items'],
            'items_detail' => $data['items_detail'],
            'months' => $data['months'],
            'total_price' => $data['total_price'],
            'status' => 'active',
            'payment_status' => 'unpaid', // unpaid by default
        ]);

        return redirect()->route('customer.payment', $booking->id);
    }

    // Step 5: Payment page
    public function payment(Booking $booking)
    {
        return view('customer.warehouses.payment', compact('booking'));
    }

    // Step 6: Store payment
    public function paymentStore(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'payment_method' => 'required|in:cash,online',
            'payment_slip' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ]);

        // Save payment slip if uploaded
        if ($request->hasFile('payment_slip')) {
            $data['payment_slip'] = $request->file('payment_slip')->store('payment_slips', 'public');
        }

        // Update booking payment info
        $booking->update([
            'payment_status' => $data['payment_method'] === 'cash' ? 'cash' : 'pending',
            'payment_method' => $data['payment_method'],
            'payment_slip' => $data['payment_slip'] ?? null,
            // Temporary placeholder QR path
            'qr_code' => "qrcodes/placeholder_booking_{$booking->id}.png",
        ]);

        // Create placeholder QR image
        if (!Storage::disk('public')->exists("qrcodes/placeholder_booking_{$booking->id}.png")) {
            Storage::disk('public')->put("qrcodes/placeholder_booking_{$booking->id}.png", 'QR code placeholder');
        }

        // Notify admin
        Notification::create([
            'user_id' => User::where('role', 'admin')->first()->id,
            'message' => "New booking #{$booking->id} for warehouse {$booking->warehouse->name}. Payment: {$data['payment_method']}",
        ]);
        // Notify owner
        Notification::create([
            'user_id' => $booking->warehouse->owner->id, // warehouse ka owner
            'message' => "New booking #{$booking->id} for your warehouse {$booking->warehouse->name}. Payment: {$data['payment_method']}. Status: " . ($data['payment_method'] === 'online' ? 'paid' : 'unpaid'),
]);


        // Notify customer
        Notification::create([
            'user_id' => $booking->customer_id,
            'message' => "Your booking #{$booking->id} is confirmed. QR code placeholder created.",
        ]);

        return redirect()->route('customer.dashboard')->with('success','Booking completed successfully.');
    }
}
