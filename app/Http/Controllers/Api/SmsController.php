<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Nexmo\Laravel\Facade\Nexmo;
use JWTAuth;
use Auth;
use App\User;
use App\UserInfo;
use Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class SmsController extends Controller
{
    public function sendSMS($phone_no)
    {
        $code = rand(100000,999999);
        Nexmo::message()->send([
            'to'   => $phone_no,
            'from' => 'Pant Poe',
            'text' => "Verification Code : $code"
        ]);

        return response()->json([
            'success'=> true,
            'data'=> [
                'code' => $code
                ]
        ],200);
    }
    public function register(Request $request)
    {
        $request->validate([
            'phone_no' => 'required',
            'password' => 'required|string',
            'role_id' => 'required'
        ]);
        $user = User::create([
            'phone_no'         => $request->phone_no,
            'password'   => Hash::make($request->password),
            'role_id' => $request->role_id
        ]);

        UserInfo::create([
            'user_id' => $user->id
        ]);
        $credentials = [
            "phone_no" => $request->phone_no,
            "password" => $request->password,
            "role_id" => $request->role_id,
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
        $token = JWTAuth::attempt([
            "phone_no" => $request->phone_no,
            "password" => $request->password,
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
    public function login(Request $request)
    {
        $request->validate([
            'phone_no' => 'required',
            'password' => 'required|string',
            'role_id' => 'required'
        ]);
        $credentials = [
            "phone_no" => $request->phone_no,
            "password" => $request->password,
            "role_id" => $request->role_id,
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

        $user = Auth::user();
        $user->remember_token = $token;
        $user->save();

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
