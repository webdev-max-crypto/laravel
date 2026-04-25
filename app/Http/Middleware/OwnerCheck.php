<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OwnerCheck
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user && $user->role === 'owner') {

            if ($user->agreement_accepted == 0) {
                return redirect()->route('owner.agreement');
            }

            if ($user->is_verified == 0) {
                return redirect()->route('owner.waiting');
            }
        }

        return $next($request);
    }
}