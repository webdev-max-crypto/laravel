<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckOwnerAgreement
{
    public function handle(Request $request, Closure $next)
{
    $user = Auth::user();

    if ($user && $user->role === 'owner') {

        // ONLY waiting check yahan rakho
        if (!$user->is_verified) {
            return redirect()->route('owner.waiting');
        }
    }

    return $next($request);
}}