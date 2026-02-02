<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    // Generate QR after payment
    public function generateQr($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->payment_status !== 'escrow' && $booking->payment_status !== 'paid') {
            return back()->with('error', 'Payment not confirmed');
        }

        $booking->update([
            'qr_code'        => Str::uuid(),
            'qr_expires_at'  => $booking->end_date,
            'expires_at'     => $booking->end_date
        ]);

        return back()->with('success', 'QR generated');
    }

    // Warehouse confirms goods stored
    public function confirmGoods($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->update([
            'goods_confirmed' => 1
        ]);

        return back()->with('success', 'Goods storage confirmed');
    }
}
