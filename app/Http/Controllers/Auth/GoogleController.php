<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Hash;   // add this
use Illuminate\Support\Str;            // add this


class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')
    ->stateless()
    ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
    ->user();

        $user = User::updateOrCreate(
            ['email' => $googleUser->email],
            [
                'name' => $googleUser->name,
                'google_id' => $googleUser->id,
                  'password' => Hash::make(Str::random(16)) // add this
            ]
        );

        Auth::login($user);

        return redirect('/dashboard');
    }
}