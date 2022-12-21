<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

class google_OAuth extends Controller
{
    public function redirectToGoogle()
    {
        // redirect user to "login with Google account" page
        return Socialite::driver('google')->redirect();
    }

    public function handleCallback()
    {
        
            $user = Socialite::driver('google')->stateless()->user();
            $finduser = User::where('social_id', $user->id)->first();

            if ($finduser)
            {
                Auth::login($finduser);
                return redirect('/dashboard');
            }
            else
            {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'social_id' => $user->id,
                    'social_type' => 'google',  
                    'password' => bcrypt('strongpassword'),
                ]);

                Auth::login($newUser);
                return redirect('/dashboard');
            }
    }
}
