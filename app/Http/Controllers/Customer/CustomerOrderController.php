<?php
// app/Http/Controllers/CustomerOrderController.php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    // Show checkout page
    public function checkout()
    {
        return view('customer.checkout');
    }

    // Process order
    public function placeOrder(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string',
            'total_amount' => 'required|numeric',
            'payment_method' => 'required|in:online,cash,offline',
            'order_details' => 'nullable|string'
        ]);

        // Create order
        $order = Order::create($validated);

        // Handle different payment methods
        if ($validated['payment_method'] === 'cash') {
            $order->payment_status = 'pending';
            $order->save();
            
            return redirect()->route('customer.order.success', $order->id)
                           ->with('message', 'Order placed! Pay cash on delivery.');
        }
        
        if ($validated['payment_method'] === 'offline') {
            $order->payment_status = 'pending';
            $order->save();
            
            return redirect()->route('customer.order.success', $order->id)
                           ->with('message', 'Order placed! Complete offline payment.');
        }

        // For online payment
        if ($validated['payment_method'] === 'online') {
            // Create pending payment record
            Payment::create([
                'order_id' => $order->id,
                'amount' => $order->total_amount,
                'payment_method' => 'pending',
                'status' => 'pending'
            ]);

            return redirect()->route('customer.payment.instructions', $order->id);
        }
    }

    // Show payment instructions
    public function paymentInstructions($orderId)
    {
        $order = Order::findOrFail($orderId);
        $adminSettings = \App\Models\AdminSetting::first();
        
        return view('customer.payment-instructions', compact('order', 'adminSettings'));
    }

    // Show order status
    public function orderStatus($orderId)
    {
        $order = Order::with('payment')->findOrFail($orderId);
        return view('customer.order-status', compact('order'));
    }
}