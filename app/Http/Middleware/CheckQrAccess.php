<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Booking;
use Illuminate\Http\Request;

class CheckQrAccess
{
    public function handle(Request $request, Closure $next)
    {
        $qr_code = $request->input('qr_code');
        $user_id = auth()->id();

        $booking = Booking::where('customer_id', $user_id)
            ->where('qr_code', $qr_code)
            ->where('goods_confirmed', 1)
            ->where('qr_expires_at', '>=', now())
            ->first();

        if (!$booking) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or expired QR'
            ], 403);
        }

        return $next($request);
    }
}
