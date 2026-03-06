<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RemindCustomerGoodsConfirm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remind-customer-goods-confirm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
   public function handle()
{

$bookings = Booking::where('payment_status','paid')
->where('goods_confirmed',0)
->get();

foreach($bookings as $booking){

$booking->user->notify(new GoodsConfirmReminder($booking));

}

}
}
