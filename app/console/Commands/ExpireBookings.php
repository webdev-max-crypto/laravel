<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ExpireBookings extends Command
{
    protected $signature = 'bookings:expire-check';
    protected $description = 'Expire old bookings and mark abandoned storage';

    public function handle()
    {
        Booking::where('status', 'active')
            ->whereDate('end_date', '<', Carbon::now())
            ->chunk(50, function ($bookings) {

                foreach ($bookings as $b) {

                    $b->update(['status' => 'expired']);
                    Log::info("Booking #{$b->id} expired.");

                    // Admin notification
                    Notification::create([
                        'user_id' => 1,
                        'type' => 'booking_expired',
                        'message' => "Booking #{$b->id} has expired."
                    ]);

                    // Customer notification
                    Notification::create([
                        'user_id' => $b->customer_id,
                        'type' => 'booking_expired',
                        'message' => "Your booking #{$b->id} has expired."
                    ]);

                    // Abandoned storage check
                    if (!$b->goods_confirmed) {

                        $b->update(['is_abandoned' => 1]);
                        Log::warning("Booking #{$b->id} marked abandoned.");

                        Notification::create([
                            'user_id' => 1,
                            'type' => 'booking_abandoned',
                            'message' => "Booking #{$b->id} marked as abandoned."
                        ]);

                        Notification::create([
                            'user_id' => $b->customer_id,
                            'type' => 'booking_abandoned',
                            'message' => "Your booking #{$b->id} is marked abandoned."
                        ]);
                    }
                }
            });

        $this->info('Booking expiry & abandoned check completed.');
    }
}
