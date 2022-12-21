<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite; 
use Illuminate\Support\Facades\Auth; 
use App\Models\User;

class OauthController extends Controller
{
    


public function githubRedirect(){
    return Socialite::driver('github')->redirect();
}




public function githubCallback() {
    $user = Socialite::driver('github')->stateless()->user();
    $this->registerOrLogin($user); 
    return redirect()->route('dashboard');
}

protected function registerOrLogin($newUser){

    $user = User::where('github', $newUser->id)->first();
    if (!$user) {
        $user = new User();
        $user->name = $newUser->nicknmae;
        $user->email = $newUser->email;
        $user->github_id = $newUser->id;
        $user->password = encrypt('password');
        $user->save(); 
    }
    Auth::login($user);
} 

 }
