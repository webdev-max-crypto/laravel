<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Notification;
use App\Models\User;

class WarehouseController extends Controller
{
    // Step 1: Book Page
    public function book($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        return view('customer.warehouses.book', compact('warehouse'));
    }

    // Step 2: Calculate Total & Show Summary
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

    // Step 3: Agreement Page
    public function agreement(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);

        // Pass all previous data to agreement
        return view('customer.warehouses.agreement', [
            'warehouse' => $warehouse,
            'data' => $request->all()
        ]);
    }

    // Step 4: Final Confirm Booking
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

        // Create booking but status is "pending" until payment
        $booking = Booking::create([
            'warehouse_id' => $warehouse->id,
            'customer_id' => $customer->id,
            'user_id' => $customer->id, // ✅ add this
            'area' => $data['area'],
            'items' => $data['items'],
            'items_detail' => $data['items_detail'],
            'months' => $data['months'],
            'total_price' => $data['total_price'],
            'status' => 'active', // not confirmed yet
        ]);

        return redirect()->route('customer.payment', $booking->id);
    }

    // Step 5: Payment Page
    public function payment(Booking $booking)
    {
        return view('customer.warehouses.payment', compact('booking'));
    }

    // Step 6: Store Payment
    public function paymentStore(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'payment_method' => 'required|in:cash,online',
            'payment_slip' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('payment_slip')) {
            $data['payment_slip'] = $request->file('payment_slip')->store('payment_slips', 'public');
        }

        $booking->update([
            'status' => $data['payment_method'] === 'online' ? 'paid' : 'cash_pending',
            'payment_method' => $data['payment_method'],
            'payment_slip' => $data['payment_slip'] ?? null,
        ]);
        return redirect()->route('customer.dashboard')
                         ->with('success','Payment submitted successfully.');

        // Notify admin and warehouse owner
        Notification::create([
            'user_id' => User::where('role','admin')->first()->id,
            'type' => 'booking',
            'message' => "New booking payment for {$booking->warehouse->name} by {$booking->customer->name}",
        ]);

        Notification::create([
            'user_id' => $booking->warehouse->owner_id,
            'type' => 'booking',
            'message' => "Booking payment received for your warehouse {$booking->warehouse->name}",
        ]);

        return redirect()->route('customer.dashboard')
                         ->with('success','Payment submitted successfully.');
    }
}
