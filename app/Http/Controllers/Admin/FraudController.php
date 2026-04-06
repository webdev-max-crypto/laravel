<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FraudReport;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Helpers\Notify;


class FraudController extends Controller
{
   public function index()
{
    $reports = FraudReport::with(['user', 'warehouse'])->latest()->get();

    return view('admin.reports.index', compact('reports'));
}

    public function resolve($id){
        $report = FraudReport::findOrFail($id);
        $report->update(['status'=>'resolved']);

        Notify::send(1,'fraud',"Fraud report #{$report->id} resolved.");
        return redirect()->back()->with('success','Fraud report resolved.');
    }
}
