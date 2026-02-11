<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Warehouse;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckInactiveWarehouses extends Command
{
    protected $signature = 'warehouses:check-inactive';
    protected $description = 'Flag inactive warehouses for admin review';

    public function handle()
    {
        $threshold = Carbon::now()->subDays(30);

        $warehouses = Warehouse::where('status', 'approved')
            ->where(function ($q) use ($threshold) {
                $q->whereNull('last_active')
                  ->orWhere('last_active', '<', $threshold);
            })
            ->where('is_flagged', 0) // prevent repeat alerts
            ->get();

        foreach ($warehouses as $w) {

            $w->update([
                'is_flagged' => 1,
                'inactive_reason' => 'No activity in last 30 days',
            ]);

            Log::warning("Warehouse {$w->id} flagged as inactive.");

            Notification::create([
                'user_id' => 1, // admin
                'type' => 'warehouse_inactive',
                'message' => "Warehouse #{$w->id} inactive for 30+ days."
            ]);
        }

        $this->info('Inactive warehouses flagged: ' . $warehouses->count());
    }
}
