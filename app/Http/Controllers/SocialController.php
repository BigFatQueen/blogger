<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use Auth;
use App\User;
use App\UserInfo;
use Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use JWTAuth;

class SocialController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }
    public function Callback($provider)
    {
        $userSocial = Socialite::driver($provider)->stateless()->user();
        $user =  User::where(['email' => $userSocial->getEmail()])->first();
        if($user){
            //Auth::login($user);
            //dd($userSocial);
            $token = JWTAuth::attempt([
                "email" => $userSocial->getEmail(),
                "password" => $userSocial->getId(),
                "status" => 1
            ]);
            $expiresAt = Carbon::now()->addMinutes(1); // keep online for 1 min
            Cache::put('active-' . Auth::user()->id, true, $expiresAt);
            // last seen
            User::where('id', Auth::user()->id)->update(['last_seen' => (new \DateTime())->format("Y-m-d H:i:s")]);
            return response()->json([
                'success'=> true,
                'data'=> [
                    'id' =>  $user->id,
                    'name' => $user->name,
                    'role' => $user->role->name,
                    'status' => 'Active',
                    'access_token' => $token,
                    'token_type' => 'Bearer'
                    ]
                ],200);
        }else{
            $user = User::create([
                    'name'          => $userSocial->getName(),
                    'email'         => $userSocial->getEmail(),
                    'password'   => Hash::make($userSocial->getId()),
                    'provider_id'   => $userSocial->getId(),
                    'provider'      => $provider,
                    'role_id' => 3
                ]);

            $user_info = UserInfo::create([
                'user_id' => $user->id,
                'dob' => '1997-01-10',
                'cover_photo' => $userSocial->getAvatar(),
                'profile_image' => $userSocial->getAvatar(),
            ]);
            $token = JWTAuth::attempt([
                "email" => $userSocial->getEmail(),
                "password" => $userSocial->getId(),
                "status" => 1
            ]);

            $expiresAt = Carbon::now()->addMinutes(1); // keep online for 1 min
            Cache::put('active-' . Auth::user()->id, true, $expiresAt);
            // last seen
            User::where('id', Auth::user()->id)->update(['last_seen' => (new \DateTime())->format("Y-m-d H:i:s")]);
            return response()->json([
                'success'=> true,
                'data'=> [
                    'id' =>  $user->id,
                    'name' => $user->name,
                    'role' => $user->role->name,
                    'status' => 'Active',
                    'access_token' => $token,
                    'token_type' => 'Bearer'
                    ]
                ],200);
        }
    }
}
