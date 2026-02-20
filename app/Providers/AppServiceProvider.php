<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Notifications\DatabaseNotification;
use App\Models\Booking;
use App\Models\CustomNotification; // aapka custom model

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
    public function boot()
    {
        // View composer for released payments
        View::composer('admin.layouts.app', function ($view) {
            $releasedPayments = Booking::where('payment_status','released')
                                ->latest()
                                ->take(3)
                                ->get();

            $view->with('releasedPayments', $releasedPayments);
        });

        // Tell Laravel to use custom notifications table
       
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