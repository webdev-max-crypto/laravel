<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\JazzCashPayoutService;

class PaymentController extends Controller
{
    public function escrow()
    {
        $payments = Payment::with(['booking.warehouse.owner'])
            ->where('status', 'escrow')
            ->paginate(20);

        return view('admin.payments.escrow', compact('payments'));
    }

    public function release($id)
    {
        $payment = Payment::with(['booking.warehouse.owner'])->findOrFail($id);

        $owner = $payment->booking->warehouse->owner ?? null;
        $mobile = $owner->mobile_number ?? null;

        // DEMO payout (no real API)
        $jazz = new JazzCashPayoutService();
        $resp = $jazz->releasePayment($payment->amount, $mobile);

        if (($resp['pp_ResponseCode'] ?? '') === '000') {

            $payment->update([
                'status' => 'released',
                'released_at' => now(),
            ]);

            return back()->with('success', 'DEMO: Payment successfully released.');
        }

        return back()->with('error', 'Payment release failed (DEMO).');
    }
}
