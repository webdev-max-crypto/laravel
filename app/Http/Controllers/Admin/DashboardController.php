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
        return view('admin.dashboard', [
            'users' => User::count(),
            'warehouses' => Warehouse::count(),
            'pendingWarehouses' => Warehouse::where('status','pending')->count(),
            'activeBookings' => Booking::where('status','active')->count(),
            'escrowPayments' => Payment::where('status','escrow')->count(),
            'paidBookings' => Booking::where('payment_status','paid')->count(),
            'releasedBookings' => Booking::where('payment_status','released')->count(),
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
