<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function index()
    {
        // Total Admin Commission
        $adminCommissionPending = Booking::where('payment_status', 'paid')
                                        ->sum('admin_commission');

        $adminCommissionReleased = Booking::where('payment_status', 'released')
                                         ->sum('admin_commission');

        $totalAdminCommission = $adminCommissionPending + $adminCommissionReleased;

        // Total Owner Balance
        $ownerBalancePending = Booking::where('payment_status', 'paid')
                                     ->sum('owner_amount');

        $ownerBalanceReleased = Booking::where('payment_status', 'released')
                                      ->sum('owner_amount');

        $totalOwnerBalance = $ownerBalancePending + $ownerBalanceReleased;

        return view('admin.balances.index', compact(
            'adminCommissionPending',
            'adminCommissionReleased',
            'totalAdminCommission',
            'ownerBalancePending',
            'ownerBalanceReleased',
            'totalOwnerBalance'
        ));
    }
}