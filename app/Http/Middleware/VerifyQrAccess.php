<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Booking;

class VerifyQrAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $qrCode = $request->header('X-QR-Code') ?? $request->input('qr_code');

        if (!$qrCode) {
            return response()->json(['error' => 'QR code missing'], 403);
        }

        $booking = Booking::where('qr_code', $qrCode)
            ->where('payment_status', 'paid')
            ->where('qr_expires_at', '>=', now())
            ->first();

        if (!$booking) {
            return response()->json(['error' => 'Invalid or expired QR'], 403);
        }

        // Optional: attach booking to request
        $request->merge(['booking' => $booking]);

        return $next($request);
    }
}
