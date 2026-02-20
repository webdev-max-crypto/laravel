<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OwnerPaymentVerified;
use Stripe\Stripe;
use Stripe\Transfer;

class OrderController extends Controller
{
    // ---------------------------------
    // Orders Page
    // ---------------------------------
    public function index()
    {
        $pendingOrders = Order::where('payment_status', 'pending')->get();
        $paidOrders = Order::where('payment_status', 'paid')->get();

        $bookings = Booking::with([
                            'customer',
                            'warehouse.owner',
                            'payment'
                        ])
                        ->orderBy('created_at','desc')
                        ->get();

        return view('admin.orders.index', compact('bookings','pendingOrders','paidOrders'));
    }

    // ---------------------------------
    // Show Release Page
    // ---------------------------------
    public function releasePage($id)
    {
        $booking = Booking::with(['customer','warehouse.owner'])
                    ->findOrFail($id);

        return view('admin.bookings.releasePage', compact('booking'));
    }

    // ---------------------------------
    // Confirm & Process Release
    // ---------------------------------
   public function confirmRelease(Request $request, $id)
{
    $booking = Booking::with('warehouse.owner')->findOrFail($id);

    if ($booking->payment_status !== 'paid') {
        return back()->with('error', 'Booking not eligible for release.');
    }
    

    $owner = $booking->warehouse->owner;
    if (!$owner) {
        return back()->with('error', 'Owner not found.');
    }

    $request->validate([
        'method' => 'required|in:stripe,jazzcash',
        'owner_jazzcash' => 'nullable|string',
        'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $amount = $booking->owner_amount ?? $booking->total_price;

    // Upload payment proof
    if ($request->hasFile('payment_proof')) {
        $file = $request->file('payment_proof');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/payment_proofs', $filename);
        $booking->update(['payment_proof' => $filename]);
    }

    try {
        // Stripe transfer
        if ($request->method === 'stripe') {
            if (!$owner->stripe_account_id) {
                return back()->with('error', 'Owner Stripe account not connected.');
            }

            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            \Stripe\Transfer::create([
                'amount' => intval($amount * 100), // amount in cents
                'currency' => 'usd',
                'destination' => $owner->stripe_account_id,
            ]);
        }

        // JazzCash transfer (assume manually done)
        if ($request->method === 'jazzcash') {
            if (!$request->owner_jazzcash) {
                return back()->with('error', 'JazzCash number required.');
            }

            $owner->update(['jazzcash_number' => $request->owner_jazzcash]);
        }

        // Update booking status to released
        $booking->update(['payment_status' => 'released']);

        // -------------------------------
        // Insert notification manually
        // -------------------------------
        \Illuminate\Support\Facades\DB::table('notifications')->insert([
            'user_id'    => $owner->id,
            'message'    => "Payment of Rs {$booking->total_price} released for booking #{$booking->id}",
            'data'       => json_encode([
                'title' => 'Payment Released',
                'message' => "Order #{$booking->id} payment released successfully",
                'order_id' => $booking->id
            ]),
            'is_read'    => 0,
            'type'       => 'OwnerPaymentVerified',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.orders.index')
                         ->with('success', 'Payment released successfully!');

    } catch (\Exception $e) {
        return back()->with('error', 'Transfer failed: ' . $e->getMessage());
    }
}}