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

        $warehouses = Warehouse::where('status','approved')
            ->whereDate('last_active','<',$threshold)
            ->get();

        foreach($warehouses as $w){
            $w->update(['status'=>'under_review']);
            Log::warning("Warehouse {$w->id} set to under_review.");

            Notification::create([
                'user_id'=>1,
                'type'=>'warehouse_inactive',
                'message'=>"Warehouse #{$w->id} inactive for 30+ days, marked under review."
            ]);
        }

        $this->info('Inactive warehouses checked: '.count($warehouses));
    }
}
