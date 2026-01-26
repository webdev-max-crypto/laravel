<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\FraudReport;

class FraudController extends Controller
{
    public function index()
    {
        $reports = FraudReport::latest()->paginate(25);
        return view('admin.fraud.index', compact('reports'));
    }

    public function resolve($id)
    {
        $r = FraudReport::findOrFail($id);
        $r->update(['status'=>'resolved']);
        return back()->with('success','Resolved');
    }
}
