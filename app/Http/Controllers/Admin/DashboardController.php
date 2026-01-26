<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\Booking;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'users' => User::count(),
            'warehouses' => Warehouse::count(),
            'pendingWarehouses' => Warehouse::where('status','pending')->count(),
            'activeBookings' => Booking::where('status','active')->count(),
            'escrowPayments' => Payment::where('status','escrow')->count(),
        ]);
    }
}
