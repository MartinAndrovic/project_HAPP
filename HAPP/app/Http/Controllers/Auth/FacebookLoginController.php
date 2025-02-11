<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NotificationToNewUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use PHPUnit\Exception;

class FacebookLoginController extends Controller
{


    public function redirect(){


        return Socialite::driver('facebook')->redirect();
    }

    public function callback(){


        try {
            $user=Socialite::driver('facebook')->stateless()->user();

            $userExisted = User::where('email', $user->email)->first();


            if ($userExisted) {
                Auth::login($userExisted);
                return redirect('/home');
            }else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'oauth_id' => $user->id,
                    'password' => Hash::make($user->id),
                ]);
                $newUser->notify(new NotificationToNewUser());
                Auth::login($newUser);
                return redirect('/home');

            }
        } catch(Exception $e) {
           dd($e);

        }
    }

}
