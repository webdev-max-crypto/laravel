<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\FraudReport;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Helpers\Notify;

class CustomerReportController extends Controller
{
    public function create($id){
        $booking = Booking::findOrFail($id);
        return view('customer.report.create', compact('booking'));
    }

    public function store(Request $request, $id){
        $booking = Booking::findOrFail($id);
        $data = $request->validate([
            'message'=>'required|string|min:5'
        ]);

        $report = FraudReport::create([
            'booking_id'=>$booking->id,
            'reported_by'=>auth()->id(),
            'message'=>$data['message'],
            'status'=>'pending'
        ]);

        Notify::send(1,'fraud',"New fraud report submitted for Booking #{$booking->id}");
        return redirect()->route('customer.dashboard')->with('success','Fraud report submitted.');
    }
}
