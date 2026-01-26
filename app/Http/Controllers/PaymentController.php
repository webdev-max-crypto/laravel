<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display all payments related to the owner’s warehouses.
     */
    public function ownerIndex()
    {
        $ownerId = Auth::id();

        // Fetch payments where booking belongs to warehouses of this owner
        $payments = Payment::whereHas('booking.warehouse', function ($query) use ($ownerId) {
            $query->where('owner_id', $ownerId);
        })
        ->with(['booking.warehouse', 'booking.customer'])
        ->orderBy('created_at', 'desc')
        ->paginate(20); // 20 payments per page

        return view('owner.payments.index', compact('payments'));
    }

    /**
     * Optional: Show a single payment details (if needed)
     */
    public function show($id)
    {
        $ownerId = Auth::id();

        $payment = Payment::with(['booking.warehouse', 'booking.customer'])
            ->whereHas('booking.warehouse', function ($query) use ($ownerId) {
                $query->where('owner_id', $ownerId);
            })
            ->findOrFail($id);

        return view('owner.payments.show', compact('payment'));
    }
}
