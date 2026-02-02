<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckExpiredBookings extends Command
{
    protected $signature = 'bookings:check-expiry';
    protected $description = 'Check for expired or abandoned bookings';

    public function handle()
    {
        $today = Carbon::today();

        $bookings = Booking::where('status','active')
            ->whereDate('end_date','<',$today)
            ->get();

        foreach($bookings as $b){
            $b->update(['status'=>'expired']);
            Log::info("Booking {$b->id} expired.");

            // Admin notification
            Notification::create([
                'user_id'=>1,
                'type'=>'booking_expired',
                'message'=>"Booking #{$b->id} expired."
            ]);

            // Customer notification
            Notification::create([
                'user_id'=>$b->customer_id,
                'type'=>'booking_expired',
                'message'=>"Your booking #{$b->id} expired."
            ]);

            if(!$b->goods_confirmed){
                $b->update(['is_abandoned'=>1]);
                Notification::create([
                    'user_id'=>1,
                    'type'=>'booking_abandoned',
                    'message'=>"Booking #{$b->id} marked abandoned."
                ]);
                Log::warning("Booking {$b->id} marked abandoned.");
            }
        }

        $this->info('Expired bookings checked: '.count($bookings));
    }
}
