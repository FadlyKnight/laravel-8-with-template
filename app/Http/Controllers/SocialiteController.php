<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }
        
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function callback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
            $finduser = User::where('provider_id', $user->id)->first();
            if($finduser){
                Auth::login($finduser);
                dd(Auth::check());
                return redirect()->intended('dashboard');
            }else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => date('Y-m-d H:i:s'),
                    'provider' => $provider,
                    'provider_id'=> $user->id,
                    'password' => encrypt('pikuniku-app')
                ]);
      
                Auth::login($newUser);
                // // datates
                // Auth::attempt(['email' => '']);
                dd(Auth::check());
      
                return redirect()->intended('dashboard');
            }
      
        } catch (ModelNotFoundException $e) {
            dd($e->getMessage());
        }
    }
    
}
