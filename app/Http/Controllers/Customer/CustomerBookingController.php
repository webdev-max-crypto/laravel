<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Warehouse;
use App\Models\Notification;
use Illuminate\Http\Request;
use Stripe\Stripe;

use App\Models\User;
use Stripe\PaymentIntent;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CustomerBookingController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    // ──────────────────────────────────────────────
    // Show booking form
    // ──────────────────────────────────────────────
    public function create($warehouse_id)
    {
        $warehouse = Warehouse::findOrFail($warehouse_id);
        return view('customer.warehouses.book', compact('warehouse'));
    }

    // ──────────────────────────────────────────────
    // Store booking via web form + notify admin
    // ──────────────────────────────────────────────
    public function store(Request $request, $warehouse_id)
    {
        $warehouse = Warehouse::findOrFail($warehouse_id);
        $customer  = auth()->user();

        $data = $request->validate([
            'area'         => 'required|integer|min:1',
            'items'        => 'required|integer|min:1',
            'months'       => 'required|integer|min:1',
            'items_detail' => 'nullable|string',
            'total_price'  => 'required|numeric',
             'delivery_option' => 'required|string', 
        ]);

          $baseTotal = $data['total_price'];

        // ✅ DELIVERY LOGIC
        $delivery  = $data['delivery_option'];
        $riderFee  = ($delivery === 'rider') ? 200 : 0;

        // ✅ FINAL TOTAL
        $finalTotal = $baseTotal + $riderFee;
        $adminCommission = $data['total_price'] * 0.10;
        $ownerAmount     = $data['total_price'] - $adminCommission;

        $booking = Booking::create([
            'warehouse_id'     => $warehouse->id,
            'customer_id'      => $customer->id,
            'user_id'          => $customer->id,
            'area'             => $data['area'],
            'items'            => $data['items'],
            'items_detail'     => $data['items_detail'],
            'months'           => $data['months'],
            'total_price'      => $data['total_price'],
            'admin_commission' => $adminCommission,
            'owner_amount'     => $ownerAmount,
            'payment_status'   => 'unpaid',
            'status'           => 'active',
            'goods_confirmed'  => 0,
            'storage_confirmed'=> 0,
            'is_abandoned'     => 0,
             'delivery_option'  => $delivery,
            'rider_fee'        => $riderFee,
            'total_price'      => $finalTotal,
             'delivery_option' => 'required|string',
        ]);

        Notification::create([
            'user_id' => 1,
            'message' => "New booking #{$booking->id} for warehouse {$warehouse->name} by {$customer->name}",
        ]);

        return redirect()->route('customer.payment', $booking->id)
                         ->with('success', 'Booking created! Proceed to payment.');
    }

    // ──────────────────────────────────────────────
    // Create Stripe PaymentIntent + Booking (API)
    // ──────────────────────────────────────────────
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'start_date'   => 'required|date',
            'months'       => 'required|integer|min:1',
            'area'         => 'required|integer|min:1',
            'items'        => 'required|integer|min:1',
            'items_detail' => 'nullable|string',
        ]);

        $warehouse = Warehouse::findOrFail($request->warehouse_id);
        $customer  = auth()->user();

        // ── Calculate amounts ──────────────────────
        $totalPrice      = $warehouse->price_per_month * $request->months;
        $adminCommission = $totalPrice * 0.10;
        $ownerAmount     = $totalPrice - $adminCommission;

        // ── Create Booking ─────────────────────────
        $booking = Booking::create([
            'user_id'          => $customer->id,
            'customer_id'      => $customer->id,
            'warehouse_id'     => $warehouse->id,
            'start_date'       => $request->start_date,
            'end_date'         => now()->parse($request->start_date)->addMonths($request->months),
            'months'           => $request->months,
            'area'             => $request->area,
            'items'            => $request->items,
            'items_detail'     => $request->items_detail,
            'total_price'      => $totalPrice,
            'admin_commission' => $adminCommission,
            'owner_amount'     => $ownerAmount,
            'payment_method'   => 'stripe',
            'payment_gateway'  => 'stripe',
            'payment_status'   => 'unpaid',
            'status'           => 'active',
            'goods_confirmed'  => 0,
            'storage_confirmed'=> 0,
            'is_abandoned'     => 0,
            'expires_at'       => now()->addMinutes(30),
        ]);

        // ── Notify admin ───────────────────────────
        Notification::create([
            'user_id' => 1,
            'message' => "New booking #{$booking->id} for warehouse {$warehouse->name} by {$customer->name}",
        ]);

        // ── Create Stripe PaymentIntent ────────────
        $intent = PaymentIntent::create([
            'amount'         => (int) ($totalPrice * 100),
            'currency'       => config('services.stripe.currency', 'pkr'),
            'capture_method' => 'manual',
            'metadata'       => [
                'booking_id'   => $booking->id,
                'customer_id'  => $customer->id,
                'warehouse_id' => $warehouse->id,
                'owner_id'     => $warehouse->owner_id,
            ],
        ]);

        // ── Save PaymentIntent reference ───────────
        $booking->update([
            'payment_ref'    => $intent->id,
            'payment_status' => 'unpaid',
        ]);

        return response()->json([
            'clientSecret' => $intent->client_secret,
            'booking_id'   => $booking->id,
            'total_price'  => $totalPrice,
        ]);
    }

    // ──────────────────────────────────────────────
    // Confirm goods safe — API (requires escrow status)
    // ──────────────────────────────────────────────
    public function confirmGoodsSafe(Request $request, $bookingId)
    {
        $booking = Booking::where('id', $bookingId)
            ->where('customer_id', auth()->id())
            ->where('payment_status', 'escrow')
            ->firstOrFail();

        $booking->update([
            'goods_confirmed' => 1,
            'status'          => 'active',
        ]);

        return response()->json([
            'message' => 'Goods confirmed! Admin will now release payment to the warehouse owner.',
        ]);
    }

    // ──────────────────────────────────────────────
    // Confirm goods — web form (marks booking completed)
    // ──────────────────────────────────────────────
   public function confirmGoods($id)
{
    $booking = Booking::findOrFail($id);

    $booking->goods_confirmed = 1;
   $booking->save();

    // Admin ko notification bhejna
    $admins = User::where('role','admin')->get();

    foreach($admins as $admin){
        Notification::create([
            'user_id' => $admin->id,
            'data'    => '',   // ← add this line
            'message' => "Customer confirmed goods for Booking #{$booking->id}. Payment can now be released."
        ]);
    
    }

    return redirect()->back()->with('success','Goods confirmed successfully. Admin has been notified.');
}
    // ──────────────────────────────────────────────
    // Generate QR code for a booking
    // ──────────────────────────────────────────────
    public function generateQr($booking_id)
    {
        $booking = Booking::where('id', $booking_id)
            ->where('customer_id', auth()->id())
            ->firstOrFail();

        if (!$booking->qr_code) {
            $booking->qr_code = QrCode::generate(
                route('customer.dashboard') . '?booking=' . $booking->id
            );
            $booking->save();
        }

        return response()->json([
            'qr_code' => $booking->qr_code,
        ]);
    }

    // ──────────────────────────────────────────────
    // Show invoice
    // ──────────────────────────────────────────────
    public function invoice($id)
    {
        $booking = Booking::with(['customer', 'warehouse'])
            ->where('id', $id)
            ->where('customer_id', auth()->id())
            ->firstOrFail();

        return view('customer.invoice', compact('booking'));
    }
}