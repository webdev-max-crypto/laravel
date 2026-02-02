<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    // Customer payment → ESCROW
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {

            $booking = Booking::findOrFail($request->booking_id);

            Payment::create([
                'booking_id'  => $booking->id,
                'customer_id' => auth()->id(),
                'owner_id'    => $booking->warehouse->owner_id,
                'amount'      => $booking->total_price,
                'status'      => 'escrow',
                'txn_ref'     => $request->txn_ref,
            ]);

            $booking->update([
                'payment_status' => 'escrow'
            ]);
        });

        return back()->with('success', 'Payment received & held in escrow');
    }

    // Admin / System → RELEASE PAYMENT
    public function release($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);

        if ($payment->status !== 'escrow') {
            return back()->with('error', 'Payment not in escrow');
        }

        DB::transaction(function () use ($payment) {

            $payment->update([
                'status'      => 'released',
                'released_at' => now()
            ]);

            Booking::where('id', $payment->booking_id)
                ->update(['payment_status' => 'paid']);
        });

        return back()->with('success', 'Payment released to owner');
    }

    // REFUND
    public function refund($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);

        DB::transaction(function () use ($payment) {

            $payment->update(['status' => 'refunded']);

            Booking::where('id', $payment->booking_id)
                ->update(['payment_status' => 'refunded']);
        });

        return back()->with('success', 'Payment refunded');
    }
}
