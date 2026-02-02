<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminEscrowController extends Controller
{
    public function index()
    {
        // Show all payments in escrow
        $payments = Payment::where('status', 'escrow')->with('booking', 'customer', 'owner')->get();
        return view('admin.payments.escrow', compact('payments'));
    }

    public function release($id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status !== 'escrow') {
            return redirect()->back()->with('error', 'Payment is not in escrow');
        }

        $payment->update([
            'status' => 'released',
            'released_at' => Carbon::now(),
        ]);

        // Notify owner
        \App\Models\Notification::create([
            'user_id' => $payment->owner_id,
            'type' => 'payment',
            'message' => "Your payment of {$payment->amount} for booking {$payment->booking_id} has been released.",
        ]);

        return redirect()->back()->with('success', 'Payment released successfully');
    }
}
