<?php

namespace App\Http\Controllers\Api;

use App\Creator;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\UserInfo;
use DB;
use Hash;
use JWTAuth;
use JWTAuthException;
use Auth;
use Cache;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Spatie\Permission\Models\Role;
use App\Http\Resources\UserInfoResource;
use App\Http\Resources\CreatorResource;

class UserController extends Controller
{
    use Authorizable;
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        $request->merge([
            'status' => 1,
        ]);
        $credentials = $request->only('email', 'password', 'role_id', 'status');
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

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone_no' => ['max:30'],
            'role_id' => ['required', 'integer' , 'max:11'],
            'dob' => ['required']
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id
            ]);

            $main ="public/users";
            $cover_folder = "$main/$user->id/covers/";
            $cover_url = "users/$user->id/covers/";

            $profile_folder = "$main/$user->id/profiles/";
            $profile_url = "users/$user->id/profiles/";

            $request->validate([
                'phone_no' => 'max:30',
                'cover_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1024',
                'profile_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1024'
            ]);
            if ($request->file(['cover_photo'])) {
                $cover_photo = $request->file(['cover_photo']);
                $cover_photo_name = date('Y-m-d H-m').$cover_photo->getClientOriginalName();
                $cover_photo_url = $cover_url.$cover_photo_name;
                $cover_photo->storeAs("$cover_folder", $cover_photo_name);
            } else {
                $cover_photo_url = "users/cover_photo.png";
            }
            if ($request->file(['profile_image'])) {
                $profile_image = $request->file(['profile_image']);
                $profile_image_name = date('Y-m-d H-m').$profile_image->getClientOriginalName();
                $profile_image_url = $profile_url.$profile_image_name;
                $profile_image->storeAs("$profile_folder", $profile_image_name);
            } else {
                $profile_image_url = "users/profile_image.png";
            }
            
            $user_info = UserInfo::create([
                'user_id' => $user->id,
                'phone_no' => $request->phone_no,
                'dob' => $request->dob,
                'cover_photo' => $cover_photo_url,
                'profile_image' => $profile_image_url,
                'embed_url' => $request->embed_url,
            ]);

            if ($request->role_id == 2) {
                
                $request->validate([
                    'categories' => ['required', 'string']
                ]);
                
                $creator = Creator::create([
                    'user_info_id' => $user_info->id,
                    'description' => $request->description
                ]);
                $creator->categories()->sync(json_decode($request->categories));
            }
            
            $role = Role::find($request->role_id);
            $user->assignRole($role->name);


            $request->merge([
                'status' => 1,
            ]);
            $credentials = $request->only('email', 'password', 'role_id', 'status');
            $token = null;
            $token = JWTAuth::attempt($credentials);

            $user = Auth::user();
            $user->remember_token = $token;
            $user->save();

            $expiresAt = Carbon::now()->addMinutes(1); // keep online for 1 min
            Cache::put('active-' . Auth::user()->id, true, $expiresAt);
            // last seen
            User::where('id', Auth::user()->id)->update(['last_seen' => (new \DateTime())->format("Y-m-d H:i:s")]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
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

    public function logout()
    {        
        Cache::forget('active-' . Auth::user()->id);
        auth()->logout();
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json([
            'success'=> true,
            'data'=> [
                    'code' => 200,
                    'message' => "Successfully Logged Out"
                ]
            ],200);
    }
 
    public function changePassword(Request $request)
    {
        $validator = validator(request()->all(), [
            'new_password' => 'required|string|min:8|confirmed',
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' =>'fail','response'=>$validator->errors() ]);
        }
        $user=User::findorfail(request()->id);
        $user->password = Hash::make(request()->new_password);
        $result = $user->save();
        if ($result>0) {
            return response()->json(['status' => true, 'response'=>'Successfully Change Password'], 200);
        } else {
            return response()->json(['status' => false, 'response'=>'Something Wrong'], 400);
        }
    }

    public function user()
    {
        $user_info = UserInfo::where('user_id', Auth::user()->id)->get()->first();
        if($user_info->user->role_id == 2) {
            $creator = Creator::where('user_info_id', $user_info->id)->get()->first();
            $data =  CreatorResource::make($creator);
        }else {
            $data =  UserInfoResource::make($user_info);
        }

        return response()->json([
            'success'=> true,
            'data'=> $data
        ],200);
    }
    
}
