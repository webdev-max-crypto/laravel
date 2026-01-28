<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $reports = []; // fetch reports from DB here
        return view('admin.reports.index', compact('reports'));
    }
}
