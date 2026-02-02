<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;

class QrScanController extends Controller
{
    public function scan(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string'
        ]);

        // Expected format: BOOKING:id|code
        if (!str_starts_with($request->qr_data, 'BOOKING:')) {
            return response()->json(['status' => false, 'message' => 'Invalid QR'], 400);
        }

        [$prefix, $data] = explode(':', $request->qr_data);
        [$bookingId, $qrCode] = explode('|', $data);

        $booking = Booking::where('id', $bookingId)
            ->where('qr_code', $qrCode)
            ->first();

        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Booking not found or QR invalid'
            ], 404);
        }

        // ❌ Expired booking
        if ($booking->end_date < now()->toDateString()) {
            return response()->json([
                'status' => false,
                'message' => 'Booking expired'
            ], 403);
        }

        // ❌ Abandoned
        if ($booking->is_abandoned == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Booking abandoned'
            ], 403);
        }

        // ❌ Payment not valid
        if (!in_array($booking->payment_status, ['escrow', 'paid'])) {
            return response()->json([
                'status' => false,
                'message' => 'Payment not verified'
            ], 403);
        }

        // ❌ Booking not active
        if ($booking->status !== 'active') {
            return response()->json([
                'status' => false,
                'message' => 'Booking not active'
            ], 403);
        }

        // ✅ ACCESS GRANTED
        return response()->json([
            'status' => true,
            'message' => 'Access granted',
            'booking_id' => $booking->id,
            'warehouse_id' => $booking->warehouse_id,
            'valid_till' => $booking->end_date
        ]);
    }
}
