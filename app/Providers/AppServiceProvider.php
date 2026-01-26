<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Role-based home route after login
     */
    public const HOME = '/redirect-role';

    /**
     * Redirect user based on role
     */
    public static function redirectToRole()
    {
        $user = Auth::user();

        if (!$user) {
            return '/login';
        }

        return match ($user->role) {
            'admin' => '/admin/dashboard',
            'owner' => '/owner/dashboard',
            'customer' => '/customer/dashboard',
            default => '/dashboard',
        };
    }
}
