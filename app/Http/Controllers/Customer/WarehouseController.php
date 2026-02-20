<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Stripe\Stripe;
use Stripe\PaymentIntent;

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
            'payment_status' => 'unpaid',
        ]);

        return redirect()->route('customer.payment', $booking->id);
    }

    // Step 5: Payment page
    public function payment(Booking $booking)
    {
        $stripePaymentIntent = null;
        $minStripePKR = 140; // minimum Stripe payment

        if($booking->total_price >= $minStripePKR){
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $stripePaymentIntent = PaymentIntent::create([
                'amount' => $booking->total_price * 100,
                'currency' => 'pkr',
                'metadata' => ['booking_id' => $booking->id],
            ]);
        }

        return view('customer.warehouses.payment', [
            'booking' => $booking,
            'paymentIntent' => $stripePaymentIntent,
            'minStripePKR' => $minStripePKR
        ]);
    }

    // Step 6: Store payment
    public function paymentStore(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'payment_method' => 'required|in:cash,online',
            'online_method'  => 'nullable|in:stripe,jazzcash',
            'payment_slip'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $paymentMethod = $data['payment_method'];
        $onlineMethod  = $data['online_method'] ?? null;

        // Require payment slip for Cash or JazzCash
        if ($paymentMethod === 'cash' || ($paymentMethod === 'online' && $onlineMethod === 'jazzcash')) {
            if (!$request->hasFile('payment_slip')) {
                return back()->withErrors(['payment_slip' => 'Payment slip is required for this payment method.'])->withInput();
            }
        }

        if ($request->hasFile('payment_slip')) {
            $data['payment_slip'] = $request->file('payment_slip')->store('payment_slips', 'public');
        }

        // Determine payment status
        if ($paymentMethod === 'cash') {
            $paymentStatus = 'cash';
        } elseif ($paymentMethod === 'online') {
            $paymentStatus = 'paid'; // Stripe or JazzCash
        } else {
            $paymentStatus = 'unpaid';
        }

        $booking->update([
            'payment_method' => $paymentMethod,
            'online_method'  => $onlineMethod,
            'payment_slip'   => $data['payment_slip'] ?? null,
            'payment_status' => $paymentStatus,
            'qr_code'        => "qrcodes/placeholder_booking_{$booking->id}.png",
        ]);

        if (!Storage::disk('public')->exists("qrcodes/placeholder_booking_{$booking->id}.png")) {
            Storage::disk('public')->put("qrcodes/placeholder_booking_{$booking->id}.png", 'QR code placeholder');
        }

        // Notifications
$admin = User::where('role','admin')->first();

if($admin){
    Notification::create([
        'user_id' => $admin->id,
        'type'    => 'new_booking',
        'message' => "New booking #{$booking->id} for warehouse {$booking->warehouse->name}. Payment: {$paymentMethod}".($onlineMethod?" ({$onlineMethod})":''),
        'data'    => json_encode([
            'booking_id' => $booking->id,
            'role'       => 'admin'
        ]),
        'is_read' => 0
    ]);
}

// Owner notification
Notification::create([
    'user_id' => $booking->warehouse->owner->id,
    'type'    => 'new_booking',
    'message' => "New booking #{$booking->id} for your warehouse {$booking->warehouse->name}. Payment: {$paymentMethod}".($onlineMethod?" ({$onlineMethod})":'').". Status: {$paymentStatus}",
    'data'    => json_encode([
        'booking_id' => $booking->id,
        'role'       => 'owner'
    ]),
    'is_read' => 0
]);

// Customer notification
Notification::create([
    'user_id' => $booking->customer_id,
    'type'    => 'booking_confirmed',
    'message' => "Your booking #{$booking->id} is confirmed. Payment status: {$paymentStatus}.",
    'data'    => json_encode([
        'booking_id' => $booking->id,
        'role'       => 'customer'
    ]),
    'is_read' => 0
]);
        return redirect()->route('customer.dashboard')->with('success','Booking completed successfully.');
    }
}
