<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WarehouseAccessController extends Controller
{
    public function enter(Request $request, $warehouseId)
    {
        $booking = $request->booking; // From middleware

        if ($booking->warehouse_id != $warehouseId) {
            return response()->json(['error' => 'Booking does not match this warehouse'], 403);
        }

        // Record access log (optional)
        // WarehouseAccess::create([
        //    'booking_id' => $booking->id,
        //    'user_id' => auth()->id(),
        //    'accessed_at' => now()
        // ]);

        return response()->json([
            'success' => true,
            'message' => 'Access granted',
            'booking_id' => $booking->id
        ]);
    }
}
