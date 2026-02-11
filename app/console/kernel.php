<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Booking;
use App\Models\Warehouse;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // 🔐 Fraud auto-check
        $schedule->command('fraud:auto-check')->daily();

        // -------------------------------
        // 1️⃣ Expire Bookings & Abandoned
        // -------------------------------
        $schedule->call(function () {

            Booking::where('status', 'active')
                ->whereDate('end_date', '<', Carbon::now())
                ->chunk(50, function ($bookings) {
                    foreach ($bookings as $b) {
                        $b->update(['status' => 'expired']);
                        Log::info("Booking #{$b->id} expired.");

                        // Notify admin
                        Notification::create([
                            'user_id' => 1, // Admin
                            'type' => 'booking_expired',
                            'message' => "Booking #{$b->id} has expired."
                        ]);

                        // Notify customer
                        Notification::create([
                            'user_id' => $b->customer_id,
                            'type' => 'booking_expired',
                            'message' => "Your booking #{$b->id} has expired."
                        ]);

                        // Mark abandoned if goods not confirmed
                        if (!$b->goods_confirmed) {
                            $b->update(['is_abandoned' => 1]);
                            Log::warning("Booking #{$b->id} marked as abandoned.");

                            Notification::create([
                                'user_id' => 1,
                                'type' => 'booking_abandoned',
                                'message' => "Booking #{$b->id} marked as abandoned."
                            ]);

                            Notification::create([
                                'user_id' => $b->customer_id,
                                'type' => 'booking_abandoned',
                                'message' => "Your booking #{$b->id} is marked as abandoned."
                            ]);
                        }
                    }
                });

        })->hourly();

        // -------------------------------
        // 2️⃣ Check Inactive Warehouses
        // -------------------------------
        $schedule->call(function () {

            Warehouse::where('status', 'approved')
                ->where(function ($q) {
                    $q->whereNull('last_active')
                      ->orWhere('last_active', '<', Carbon::now()->subDays(30));
                })
                ->chunk(50, function ($warehouses) {
                    foreach ($warehouses as $wh) {
                        // ✅ Use flag instead of overwriting status
                        $wh->update([
                            'is_flagged' => 1,
                            'inactive_reason' => 'No activity in last 30 days'
                        ]);

                        Log::warning("Warehouse #{$wh->id} flagged inactive.");

                        // Notify admin
                        Notification::create([
                            'user_id' => 1,
                            'type' => 'warehouse_inactive',
                            'message' => "Warehouse #{$wh->id} inactive for 30+ days."
                        ]);
                    }
                });

        })->daily();

        // ✅ Remove duplicate inline warehouse logic
        // $schedule->command('warehouses:check-inactive')->daily();
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
