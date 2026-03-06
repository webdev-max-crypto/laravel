<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                $request->header('Stripe-Signature'),
                config('services.stripe.webhook_secret')
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        match ($event->type) {

            // ✅ Customer card authorized → funds held in escrow
            'payment_intent.amount_capturable_updated' => (function($intent) {
                $booking = Booking::where('payment_ref', $intent->id)->first();
                if ($booking) {
                    $booking->update([
                        'payment_status' => 'escrow',    // ← your enum value
                        'is_abandoned'   => 0,
                    ]);
                }
            })($event->data->object),

            // ✅ Admin captured payment → fully paid
            'payment_intent.succeeded' => (function($intent) {
                $booking = Booking::where('payment_ref', $intent->id)->first();
                if ($booking) {
                    $booking->update([
                        'payment_status' => 'paid',      // ← your enum value
                        'payment_slip'   => $intent->charges->data[0]->receipt_url ?? null,
                    ]);
                }
            })($event->data->object),

            // ❌ Payment failed → mark abandoned
            'payment_intent.payment_failed' => (function($intent) {
                $booking = Booking::where('payment_ref', $intent->id)->first();
                if ($booking) {
                    $booking->update([
                        'payment_status' => 'unpaid',
                        'is_abandoned'   => 1,           // ← your column
                    ]);
                }
            })($event->data->object),

            default => null,
        };

        return response()->json(['status' => 'received']);
    }
}