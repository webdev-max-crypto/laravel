<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Order;

class DashboardController extends Controller
{
    // -----------------------------
    // Admin Dashboard Main View
    // -----------------------------
  public function index()
    {
        // Fetch all relevant bookings
        $bookings = Booking::whereIn('payment_status', ['paid','escrow','released'])->get();

        $totalCommission = 0;
        $commissionPending = 0;
        $commissionReleased = 0;

        foreach($bookings as $booking){
            // Calculate admin commission (default 10% if not set)
            $commission = $booking->admin_commission ?? ($booking->total_price * 0.1);
            $totalCommission += $commission;

            if($booking->payment_status === 'released'){
                $commissionReleased += $commission;
            } else {
                $commissionPending += $commission;
            }
        }

        return view('admin.dashboard', [
            'users' => User::count(),
            'warehouses' => Warehouse::count(),
            'pendingWarehouses' => Warehouse::where('status','pending')->count(),
            'activeBookings' => Booking::where('status','active')->count(),
            'escrowPayments' => Booking::whereIn('payment_status',['paid','escrow'])->count(),
            'paidBookings' => Booking::where('payment_status','paid')->count(),
            'releasedBookings' => Booking::where('payment_status','released')->count(),

            'totalCommission' => $totalCommission,
            'adminCommissionPending' => $commissionPending,
            'adminCommissionReleased' => $commissionReleased,
        ]);
    }

    // -----------------------------
    // Detailed Stats View
    // -----------------------------
    public function stats()
    {
        $pendingOrders = Order::where('payment_status', 'pending')->count();
        $paidOrders = Order::where('payment_status', 'paid')->count();
        $releasedOrders = Order::where('payment_status', 'released')->count();
        $flaggedWarehouses = Warehouse::where('is_flagged', 1)->count();
        $inactiveWarehouses = Warehouse::where('status', 'under_review')->count();
        $ownersConnected = User::where('role','owner')->whereNotNull('stripe_account_id')->count();
        $ownersPendingStripe = User::where('role','owner')->whereNull('stripe_account_id')->count();

        return view('admin.stats', compact(
            'pendingOrders', 
            'paidOrders', 
            'releasedOrders', 
            'flaggedWarehouses', 
            'inactiveWarehouses',
            'ownersConnected',
            'ownersPendingStripe'
        ));
    }
}
