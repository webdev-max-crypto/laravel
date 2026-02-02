<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Helpers\Notify;

class PaymentController extends Controller
{
    // Show payments in escrow
    public function escrow()
    {
        $payments = Payment::where('status','escrow')->latest()->get();
        return view('admin.payments.escrow', compact('payments'));
    }

    // Release payment to owner
    public function release($id)
    {
        $payment = Payment::findOrFail($id);

        if($payment->status !== 'escrow'){
            return redirect()->back()->with('error','Payment not in escrow.');
        }

        $payment->update([
            'status'=>'released',
            'released_at'=>now()
        ]);

        // Notify owner & admin
        Notify::send($payment->owner_id,'payment',"Your payment #{$payment->id} has been released.");
        Notify::send(1,'payment',"Payment #{$payment->id} released to owner.");

        return redirect()->back()->with('success','Payment released successfully.');
    }

    // Refund payment
    public function refund($id)
    {
        $payment = Payment::findOrFail($id);

        if(!in_array($payment->status,['escrow','released'])){
            return redirect()->back()->with('error','Cannot refund this payment.');
        }

        $payment->update([
            'status'=>'refunded'
        ]);

        // Notify customer & admin
        Notify::send($payment->customer_id,'payment',"Your payment #{$payment->id} has been refunded.");
        Notify::send(1,'payment',"Payment #{$payment->id} refunded to customer.");

        return redirect()->back()->with('success','Payment refunded successfully.');
    }
}
