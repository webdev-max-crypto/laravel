<?php
namespace App\Console;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Booking, App\Models\Warehouse;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            Booking::where('status','active')
                ->whereDate('end_date','<', now())
                ->chunk(50, function ($bookings) {
                    foreach ($bookings as $b) {
                        $b->update(['status' => 'expired']);
                        Log::info("Booking {$b->id} expired.");
                    }
                });
        })->hourly();

        $schedule->call(function () {
            Warehouse::where('status','approved')
                ->whereDate('last_active','<', now()->subDays(30))
                ->chunk(50, function ($items) {
                    foreach ($items as $wh) {
                        $wh->update(['status' => 'under_review']);
                        Log::warning("Warehouse {$wh->id} under_review.");
                    }
                });
        })->daily();
    }

    protected function commands() { $this->load(__DIR__.'/Commands'); }
}
