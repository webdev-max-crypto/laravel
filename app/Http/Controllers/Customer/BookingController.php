<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BookingController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'start_date'   => 'required|date',
            'months'       => 'required|integer|min:1',
            'area'         => 'required|integer',
            'items'        => 'required|integer',
            'items_detail' => 'nullable|string',
        ]);

        $warehouse = Warehouse::findOrFail($request->warehouse_id);

        // ── Calculate amounts ──────────────────────
        $totalPrice      = $warehouse->price_per_month * $request->months;
        $adminCommission = $totalPrice * 0.10;   // 10% your cut
        $ownerAmount     = $totalPrice - $adminCommission;

        // ── Create Booking ─────────────────────────
        $booking = Booking::create([
            'user_id'          => auth()->id(),
            'customer_id'      => auth()->id(),
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
            'expires_at'       => now()->addMinutes(30), // payment window
        ]);

        // ── Create Stripe PaymentIntent ────────────
        $intent = PaymentIntent::create([
            'amount'         => $totalPrice * 100,  // convert to cents
            'currency'       => 'pkr',              // change to your currency
            'capture_method' => 'manual',           // HOLD funds in escrow
            'metadata'       => [
                'booking_id'   => $booking->id,
                'customer_id'  => auth()->id(),
                'warehouse_id' => $warehouse->id,
                'owner_id'     => $warehouse->owner_id,
            ],
        ]);

        // ── Save PaymentIntent ID in payment_ref ──
        $booking->update([
            'payment_ref'  => $intent->id,           // pi_xxxxxxxxxxxxxxx
            'payment_status' => 'unpaid',
        ]);

        return response()->json([
            'clientSecret' => $intent->client_secret,
            'booking_id'   => $booking->id,
            'total_price'  => $totalPrice,
        ]);
    }

    // Customer confirms goods are safe inside warehouse
    public function confirmGoodsSafe(Request $request, $bookingId)
    {
        $booking = Booking::where('id', $bookingId)
            ->where('customer_id', auth()->id())
            ->where('payment_status', 'escrow')   // must be in escrow
            ->firstOrFail();

        $booking->update([
            'goods_confirmed' => 1,   // ← your column
            'status'          => 'active',
        ]);

        return response()->json([
            'message' => 'Goods confirmed! Admin will now release payment to the warehouse owner.'
        ]);
    }
}