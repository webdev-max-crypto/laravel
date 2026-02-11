<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OwnerPaymentVerified;
use Stripe\Stripe;
use Stripe\Transfer;

class OrderController extends Controller
{
    // -----------------------------
    // Orders page
    // -----------------------------
    public function index()
    {
        $pendingOrders = Order::where('payment_status', 'pending')->get();
        $paidOrders = Order::where('payment_status', 'paid')->get();
        $bookings = Booking::with(['customer','owner','warehouse','payment'])->orderBy('created_at','desc')->get();

        return view('admin.orders.index', compact('bookings','pendingOrders', 'paidOrders'));
    }

    // -----------------------------
    // Admin release payment to owner (Stripe)
    // -----------------------------
    public function releasePayment($bookingId)
    {
        $booking = Booking::with('owner')->findOrFail($bookingId);

        if($booking->payment_status !== 'paid'){
            return redirect()->back()->with('error', 'Booking not paid yet.');
        }

        if(!$booking->owner || !$booking->owner->stripe_account_id){
            return redirect()->back()->with('error', 'Owner Stripe account not connected.');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            Transfer::create([
                'amount' => $booking->owner_amount * 100, // cents
                'currency' => 'usd',
                'destination' => $booking->owner->stripe_account_id,
            ]);

            $booking->payment_status = 'released';
            $booking->save();

            // Notify Owner
            if($booking->owner){
                Notification::send($booking->owner, new OwnerPaymentVerified($booking));
            }

            return redirect()->back()->with('success', 'Payment released to owner successfully!');
        } catch (\Exception $e){
            return redirect()->back()->with('error', 'Stripe transfer failed: '.$e->getMessage());
        }
    }

    // -----------------------------
    // Receive SMS (payment verification)
    // -----------------------------
    public function receiveSMS(Request $request)
    {
        $validated = $request->validate([
            'sender' => 'required|string',
            'message' => 'required|string',
            'received_at' => 'required|date'
        ]);

        $transactionData = $this->parseBankSMS($validated['message']);

        if (!$transactionData) {
            return response()->json(['success' => false]);
        }

        $payment = Payment::where('status', 'pending')
            ->where('amount', $transactionData['amount'])
            ->first();

        if (!$payment) {
            return response()->json(['success' => false]);
        }

        // Update payment
        $payment->update([
            'transaction_id' => $transactionData['transaction_id'],
            'payment_method' => $transactionData['method'],
            'sms_content' => $validated['message'],
            'status' => 'verified',
            'payment_date' => $validated['received_at']
        ]);

        // Update booking
        $booking = $payment->booking;
        if($booking){
            $booking->update(['payment_status'=>'paid']);
            // Notify Owner
            if($booking->owner){
                Notification::send($booking->owner, new OwnerPaymentVerified($booking));
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment verified & owner notified'
        ]);
    }

    // -----------------------------
    // Parse Bank SMS
    // -----------------------------
    private function parseBankSMS($message)
    {
        if(stripos($message,'jazz')!==false){
            preg_match('/Rs\.?\s*([\d,]+)/i',$message,$amount);
            preg_match('/TID[:\s]*([\w\d]+)/i',$message,$tid);

            return [
                'method'=>'JazzCash',
                'amount'=>floatval(str_replace(',','',$amount[1] ?? 0)),
                'transaction_id'=>$tid[1] ?? null
            ];
        }

        if(stripos($message,'easypaisa')!==false){
            preg_match('/Rs\.?\s*([\d,]+)/i',$message,$amount);
            preg_match('/ref[:\s]*([\w\d]+)/i',$message,$ref);

            return [
                'method'=>'EasyPaisa',
                'amount'=>floatval(str_replace(',','',$amount[1] ?? 0)),
                'transaction_id'=>$ref[1] ?? null
            ];
        }

        return null;
    }
}
