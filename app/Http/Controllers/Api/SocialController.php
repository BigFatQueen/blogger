<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

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
    public function redirect()
    {
        // return 'helo';
        return Socialite::driver('google')->redirect();
        // return Response::json([
        //     'url' => Socialite::driver($provider)->stateless()->redirect()->getTargetUrl(),
        // ]);
    }
    public function callback($provider)
    {
        $userSocial = Socialite::driver($provider)->user();

        $user =  User::where(['email' => $userSocial->getEmail()])->first();
        if($user){
            $credentials = [
                "email" => $userSocial->getEmail(),
                "password" => $userSocial->getId(),
                "status" => 1
            ];

            $token = null;
            try {
                if (!$token = JWTAuth::attempt($credentials)) {
                    return response()->json([
                        'success'=> false,
                        'errors'=> [
                                'code' => 400,
                                'message' => "Invalid Login User"
                            ]
                        ],400);
                }
            } catch (JWTAuthException $e) {
                return response()->json([
                    'success'=> false,
                    'errors'=> [
                            'code' => 400,
                            'message' => "Failed to create token"
                        ]
                    ],400);
            }

            $token = JWTAuth::attempt($credentials);
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
            $user->assignRole('user');

            $user_info = UserInfo::create([
                'user_id' => $user->id,
                'cover_photo' => $userSocial->getAvatar(),
                'profile_image' => $userSocial->getAvatar(),
            ]);

            $credentials = [
                "email" => $userSocial->getEmail(),
                "password" => $userSocial->getId(),
                "status" => 1
            ];

            $token = null;
            try {
                if (!$token = JWTAuth::attempt($credentials)) {
                    return response()->json([
                        'success'=> false,
                        'errors'=> [
                                'code' => 400,
                                'message' => "Invalid Login User"
                            ]
                        ],400);
                }
            } catch (JWTAuthException $e) {
                return response()->json([
                    'success'=> false,
                    'errors'=> [
                            'code' => 400,
                            'message' => "Failed to create token"
                        ]
                    ],400);
            }

            $token = JWTAuth::attempt($credentials);

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

    public function login(Request $request){

       $user =  User::where(['email' => $request->email])->first();
       if($user){
            $credentials = [
                "email" => $request->email,
                "password" => $request->token,
                "status" => 1
            ];

            $token = null;
            try {
                if (!$token = JWTAuth::attempt($credentials)) {
                    return response()->json([
                        'success'=> false,
                        'errors'=> [
                                'code' => 400,
                                'message' => "Invalid Login User"
                            ]
                        ],400);
                }
            } catch (JWTAuthException $e) {
                return response()->json([
                    'success'=> false,
                    'errors'=> [
                            'code' => 400,
                            'message' => "Failed to create token"
                        ]
                    ],400);
            }

            $token = JWTAuth::attempt($credentials);
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
                    'name'          => $request->name,
                    'email'         => $request->email,
                    'password'   => Hash::make($request->token),
                    'provider_id'   => $request->token,
                    'provider'      => $request->provider,
                    'role_id' => 3
                ]);

            $user->assignRole('user');

         $user_info = UserInfo::create([
                'user_id' => $user->id,
                'cover_photo' => $request->image,
                'profile_image' => $request->image,
            ]);
            
            $credentials = [
                "email" => $request->email,
                "password" => $request->token,
                "status" => 1
            ];
            $token = null;
            try {
                if (!$token = JWTAuth::attempt($credentials)) {
                    return response()->json([
                        'success'=> false,
                        'errors'=> [
                                'code' => 400,
                                'message' => "Invalid Login User"
                            ]
                        ],400);
                }
            } catch (JWTAuthException $e) {
                return response()->json([
                    'success'=> false,
                    'errors'=> [
                            'code' => 400,
                            'message' => "Failed to create token"
                        ]
                    ],400);
            }

            $token = JWTAuth::attempt($credentials);

            $expiresAt = Carbon::now()->addMinutes(1); // keep online for 1 min
            Cache::put('active-' . Auth::user()->id, true, $expiresAt);
            // last seen
            User::where('id', Auth::user()->id)->update(['last_seen' => (new \DateTime())->format("Y-m-d H:i:s")]);
            return response()->json([
                'success'=> true,
                'data'=> [
                    'id' =>  $user->id,
                    'name' => $user->name,
                    'profile_image' => $user->userInfo->profile_image,
                    'role' => $user->role->name,
                    'status' => 'Active',
                    'access_token' => $token,
                    'token_type' => 'Bearer'
                    ]
                ],200);
       }
    }
}
