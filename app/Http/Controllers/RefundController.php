<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    // Customer or Owner: request a refund
    public function request(Request $request, Booking $booking)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
            'amount' => 'required|numeric|min:1',
        ]);

        $me    = auth()->user();
        $admin = User::where('role', 'admin')->first();

        $exists = Refund::where('booking_id', $booking->id)
            ->where('requester_id', $me->id)
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return back()->with('error', 'You already have a pending refund request for this booking.');
        }

        Refund::create([
            'booking_id'   => $booking->id,
            'requester_id' => $me->id,
            'receiver_id'  => $admin->id,
            'from_role'    => $me->role,
            'to_role'      => 'admin',
            'amount'       => $request->amount,
            'reason'       => $request->reason,
            'status'       => 'pending',
        ]);

        return back()->with('success', 'Refund request submitted. Admin will review it shortly.');
    }

    // Admin: view all refund requests
    public function adminIndex()
    {
        $refunds = Refund::with(['booking.warehouse', 'requester', 'receiver'])
            ->latest()
            ->paginate(20);

        return view('admin.refunds.index', compact('refunds'));
    }

    // Admin: approve a refund
    public function approve(Request $request, Refund $refund)
    {
        $request->validate([
            'to_role'    => 'required|in:customer,owner',
            'admin_note' => 'nullable|string|max:500',
        ]);

        $booking  = $refund->booking;
        $receiver = $request->to_role === 'customer'
            ? $booking->customer
            : $booking->warehouse->owner;

        $refund->update([
            'status'       => 'approved',
            'to_role'      => $request->to_role,
            'receiver_id'  => $receiver->id,
            'admin_note'   => $request->admin_note,
            'processed_at' => now(),
        ]);

        // Use 'released' — valid enum value in bookings table
        $booking->update(['payment_status' => 'released']);

        return back()->with('success', "Refund of Rs {$refund->amount} approved and sent to {$receiver->name}.");
    }

    // Admin: reject a refund
    public function reject(Request $request, Refund $refund)
    {
        $request->validate(['admin_note' => 'required|string|max:500']);

        $refund->update([
            'status'       => 'rejected',
            'admin_note'   => $request->admin_note,
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Refund request rejected.');
    }

    // Admin: send refund directly
    public function adminSend(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'to_role'    => 'required|in:customer,owner',
            'amount'     => 'required|numeric|min:1',
            'reason'     => 'required|string|max:1000',
        ]);

        $admin    = auth()->user();
        $booking  = Booking::with(['customer', 'warehouse.owner'])->findOrFail($request->booking_id);
        $receiver = $request->to_role === 'customer' ? $booking->customer : $booking->warehouse->owner;

        Refund::create([
            'booking_id'   => $booking->id,
            'requester_id' => $admin->id,
            'receiver_id'  => $receiver->id,
            'from_role'    => 'admin',
            'to_role'      => $request->to_role,
            'amount'       => $request->amount,
            'reason'       => $request->reason,
            'status'       => 'approved',
            'processed_at' => now(),
        ]);

        // Use 'released' — valid enum value in bookings table
        $booking->update(['payment_status' => 'released']);

        return back()->with('success', "Refund of Rs {$request->amount} sent to {$receiver->name}.");
    }

    // Customer / Owner: view their refunds
    public function myRefunds()
    {
        $me      = auth()->user();
        $refunds = Refund::with(['booking.warehouse'])
            ->where('requester_id', $me->id)
            ->orWhere('receiver_id', $me->id)
            ->latest()
            ->get();

        $view = $me->role === 'owner' ? 'owner.refunds.index' : 'customer.refunds.index';
        return view($view, compact('refunds'));
    }
}
