<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class AutoFraudCheck extends Command
{
    protected $signature = 'fraud:auto-check';
    protected $description = 'Detect suspicious bookings & warehouses';

    public function handle()
    {
        // Customers with 3+ cancelled bookings
        $suspiciousCustomers = Booking::select('customer_id')
            ->where('status','cancelled')
            ->groupBy('customer_id')
            ->havingRaw('COUNT(*) >= 3')
            ->pluck('customer_id');

        foreach($suspiciousCustomers as $customerId){
            Notification::create([
                'user_id'=>1,
                'type'=>'fraud',
                'message'=>"Customer #{$customerId} flagged for repeated cancellations."
            ]);
            Log::warning("Customer #{$customerId} flagged for fraud.");
        }

        $this->info('Fraud check completed.');
    }
}
