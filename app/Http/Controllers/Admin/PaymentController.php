<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\Notify;
use App\Models\Booking;
use App\Models\Warehouse;

class PaymentController extends Controller
{
    // Show payments in escrow
   public function escrow()
    {
         $bookings = Booking::with('owner')
        ->where('status','paid')
        ->get();

        $total = $bookings->sum('total_price');

        return view('admin.payments.escrow', compact('bookings','total'));
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

        // Update owner balance automatically
        $owner = User::find($payment->owner_id);
        if($owner){
            $owner->balance = ($owner->balance ?? 0) + $payment->amount;
            $owner->save();
        }

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

        // Optionally reduce owner's balance if refunded
        $owner = User::find($payment->owner_id);
        if($owner && $payment->status == 'released'){
            $owner->balance = ($owner->balance ?? 0) - $payment->amount;
            $owner->save();
        }

        // Notify customer & admin
        Notify::send($payment->customer_id,'payment',"Your payment #{$payment->id} has been refunded.");
        Notify::send(1,'payment',"Payment #{$payment->id} refunded to customer.");

        return redirect()->back()->with('success','Payment refunded successfully.');
    }

// Admin view of all owners balances
// Admin view of all owners balances
public function ownersBalance()
{
    $owners = User::where('role', 'owner')->get();

    $totalOwnerBalance = 0;
    $totalAdminCommission = 0;

    $ownerBalancePending = 0;
    $ownerBalanceReleased = 0;

    $adminCommissionPending = 0;
    $adminCommissionReleased = 0;

    foreach ($owners as $owner) {
        // Owner's warehouses
        $warehouseIds = Warehouse::where('owner_id', $owner->id)->pluck('id');

        // Bookings for this owner
        $bookings = Booking::whereIn('warehouse_id', $warehouseIds)
            ->whereIn('payment_status', ['paid','released','escrow'])
            ->get();

        $ownerBalance = 0;

        foreach ($bookings as $booking) {
            // Calculate owner amount and admin commission if missing
            $bookingOwnerAmount = $booking->owner_amount ?? ($booking->total_price * 0.9);
            $bookingAdminCommission = $booking->admin_commission ?? ($booking->total_price * 0.1);
            $booking->save(); // save calculated amounts if missing

            // Split balances by payment status
            if ($booking->payment_status === 'released') {
                $ownerBalanceReleased += $bookingOwnerAmount;
                $adminCommissionReleased += $bookingAdminCommission;
            } else { // paid or escrow
                $ownerBalancePending += $bookingOwnerAmount;
                $adminCommissionPending += $bookingAdminCommission;
            }

            $ownerBalance += $bookingOwnerAmount;
            $totalAdminCommission += $bookingAdminCommission;
        }

        $owner->balance = $ownerBalance;
        $totalOwnerBalance += $ownerBalance;
    }

    // Total paid bookings
    $totalPaid = Booking::whereIn('payment_status', ['paid','released','escrow'])->sum('total_price');

    return view('admin.balances.index', compact(
        'owners',
        'totalOwnerBalance',
        'totalPaid',
        'totalAdminCommission',
        'ownerBalancePending',
        'ownerBalanceReleased',
        'adminCommissionPending',
        'adminCommissionReleased'
    ));
}}