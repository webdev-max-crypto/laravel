<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // assuming owners are stored in users table

class OwnerController extends Controller
{
    public function index()
    {
        // Get all owners
        $owners = User::where('role', 'owner')->get();

        return view('admin.owners.index', compact('owners'));
    }
}
