<?php
namespace App\Http\Controllers;

use App\Creator;
use App\Region;
use App\UserInfoSocialLink;
use Illuminate\Http\Request;
use App\Helper\Log;
use App\Helper\UserHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use App\UserInfo;
use Illuminate\Support\Facades\Hash;
use Cache;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) 
    {   
        $status = null;
        $roles = Role::all();
        $permissions = Permission::all();
        $users = User::where('role_id', 3)->orderBy('id', 'desc')->paginate(10);
        if ($request->ajax()) {
            
            $filter_arr = [];
            $role_id = 3;
            $permission_id = $request->permission_id;
            $keyword = $request->keyword;

            $filter_arr['role_id'] = $role_id;
            $filter_arr['permission_id'] = $permission_id;
            $filter_arr['keyword'] = $keyword;
            
            $users = UserHelper::search($role_id, $permission_id, $keyword);
            return view('backend.user.table', compact('users', 'status', 'filter_arr'))->render();
        }else {
            return view('backend.user.index', compact('users', 'status', 'roles', 'permissions'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {           
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Facility  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = \App\Helper\Crypt::crypt()->decrypt( $id );
        $user = User::find($id);
        return view('backend.user.show', compact('user'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Facility  $role
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $id = \App\Helper\Crypt::crypt()->decrypt( $id );
        $user = User::where('id', $id)->first();
        $roles = Role::orderBy('id', 'desc')->get();
        $permissions = Permission::orderBy('id', 'desc')->get();
        $regions = Region::all();
        $user_permissions = [];
        foreach ($user->permissions as $value) {
            array_push($user_permissions, $value->name);
        }
        return view('backend.user.edit',compact('user' ,'roles', 'permissions', 'user_permissions', 'regions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Facility  $facility
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = \App\Helper\Crypt::crypt()->decrypt($id);
        $request->validate([
            "name"=> 'required|min:3|max:50',
        ]);
        if ($request->change_pwd) {
            $request->validate([
                'password' => 'required|string|confirmed',
            ]);
        }

        if ($request->phone_1) {
            $request->validate([
                'phone_1' => 'max:30',
            ]);
        }

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_no = $request->phone_1;
        if ($request->change_pwd) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $main ="public/users";
        $cover_folder = "$main/$user->id/covers/";
        $cover_url = "users/$user->id/covers/";

        $profile_folder = "$main/$user->id/profiles/";
        $profile_url = "users/$user->id/profiles/";

        if ($request->phone_2) {
            $request->validate([
                'phone_2' => 'max:30',
            ]);
        }

        if ($request->gender) {
            $request->validate([
                'gender' => 'max:30',
            ]);
        }

        if ($request->profile_url) {
            $request->validate([
                'profile_url' => 'max:255',
            ]);
        }

        if ($request->file(['cover_photo'])) {
            $request->validate([
                'cover_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1024'
            ]);
            $cover_photo = $request->file(['cover_photo']);
            $cover_photo_name = date('Y-m-dH-m'). \uniqid();
            $cover_photo_url = $cover_url.$cover_photo_name;
            $cover_photo->storeAs("$cover_folder", $cover_photo_name);
        } else {
            $cover_photo_url = $request->cover_photo;
        }
        if ($request->file(['profile_image'])) {
            $request->validate([
                'profile_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1024'
            ]);
            $profile_image = $request->file(['profile_image']);
            $profile_image_name = date('Y-m-dH-m'). \uniqid();
            $profile_image_url = $profile_url.$profile_image_name;
            $profile_image->storeAs("$profile_folder", $profile_image_name);
        } else {
            $profile_image_url = $request->profile_image;
        }
        $user_info = UserInfo::where('user_id', $user->id)->get()->first();
        $user_info->user_id = $user->id;
        $user_info->region_id = $request->region_id;
        $user_info->address = $request->address;
        $user_info->phone_no = $request->phone_2;
        $user_info->gender = $request->gender;
        $user_info->dob = $request->dob;
        $user_info->cover_photo = $cover_photo_url;
        $user_info->profile_image = $profile_image_url;
        $user_info->bio = $request->bio;
        $user_info->profile_url = $request->profile_url;
        $user_info->save();


        $social_names = $request->social_name;    
        $social_links = $request->link;    
        if ($social_names != null && $social_links != null) {
            $user_info_social_links = UserInfoSocialLink::where('user_info_id', $user_info->id)->get();
            foreach ($user_info_social_links as $key => $user_info_social_link) {
                $user_info_social = UserInfoSocialLink::find($user_info_social_link->id);
                $user_info_social->user_info_id = $user_info->id;
                $user_info_social->name = $social_names[$key];
                $user_info_social->link = $social_links[$key];
                $user_info_social->save();
            }
        }

        if ($request->permissions) {
            $user->permissions()->sync($request->permissions);
        }

        if ($request->logout && $request->change_pwd) {
            Auth::logoutOtherDevices($user->password);
        }
        return redirect()->route('admin.user.index')->with('status','User was successfully updated!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Facility  $facility
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = \App\Helper\Crypt::crypt()->decrypt($id);
        $user = User::find($id);    
        $user->delete();
        return redirect()->route('admin.user.index')->with('status','User was successfully deleted!!');
        
    }
    
    public function inActive(Request $request, $id)
    {
        $id = \App\Helper\Crypt::crypt()->decrypt($id);
        $user = User::find($id);
        $user->status = $request->status;  
        $user->save();
        return redirect()->route('admin.user.index')->with('status','User successfully inactive!!');
    }
}
