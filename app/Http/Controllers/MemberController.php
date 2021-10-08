<?php
namespace App\Http\Controllers;

use App\Category;
use App\Creator;
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
class MemberController extends Controller
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
        $members = User::where('role_id', 2)->orderBy('id', 'desc')->paginate(10);
        if ($request->ajax()) {
            
            $filter_arr = [];
            $role_id = 2;
            $permission_id = $request->permission_id;
            $keyword = $request->keyword;

            $filter_arr['role_id'] = $role_id;
            $filter_arr['permission_id'] = $permission_id;
            $filter_arr['keyword'] = $keyword;
            $members = UserHelper::search($role_id, $permission_id, $keyword);
            return view('backend.member.table', compact('members', 'status', 'filter_arr'))->render();
        }else {
            return view('backend.member.index', compact('members', 'status', 'roles', 'permissions'));
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
        return view('backend.member.show', compact('user'));

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
        $roles = Role::where('id', '!=', 1)->orderBy('id', 'desc')->get();
        $permissions = Permission::orderBy('id', 'desc')->get();
        $user_permissions = [];
        foreach ($user->permissions as $value) {
            array_push($user_permissions, $value->name);
        }
        $creator = Creator::find($user->userInfo->creator->id);
        $categories = Category::all();
        $user_categories = [];
        foreach ($creator->categories as $value) {
            array_push($user_categories, $value->name);
        }
        return view('backend.member.edit',compact('user' ,'roles', 'permissions', 'user_permissions', 'categories', 'user_categories'));
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
            'email' => 'required|string|email|max:255',
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

        if ($request->file(['cover_photo'])) {
            $request->validate([
                'cover_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1024'
            ]);
            $cover_photo = $request->file(['cover_photo']);
            $cover_photo_name = date('Y-m-d H-m').$cover_photo->getClientOriginalName();
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
            $profile_image_name = date('Y-m-d H-m').$profile_image->getClientOriginalName();
            $profile_image_url = $profile_url.$profile_image_name;
            $profile_image->storeAs("$profile_folder", $profile_image_name);
        } else {
            $profile_image_url = $request->profile_image;
        }

        DB::beginTransaction();
        try {

            $user_info = UserInfo::where('user_id', $user->id)->get()->first();
            $user_info->user_id = $user->id;
            $user_info->phone_no = $request->phone_2;
            $user_info->dob = $request->dob;
            $user_info->cover_photo = $cover_photo_url;
            $user_info->profile_image = $profile_image_url;
            $user_info->embed_url = $request->embed_url;
            $user_info->save();
      
            $request->validate([
                'categories' => ['required']
            ]);
            
            $creator = Creator::where('user_info_id', $user_info->id)->get()->first();
            $creator->user_info_id = $user_info->id;
            $creator->description = $request->description;
            $creator->save();
            $creator->categories()->sync($request->categories);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        if ($request->permissions) {
            $user->permissions()->sync($request->permissions);
        }

        if ($request->logout && $request->change_pwd) {
            Auth::logoutOtherDevices($user->password);
        }
        return redirect()->route('admin.member.index')->with('status','Member was successfully updated!!');
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
        return redirect()->route('admin.member.index')->with('status','Member was successfully deleted!!');
        
    }
    
    public function inActive(Request $request, $id)
    {
        $id = \App\Helper\Crypt::crypt()->decrypt($id);
        $user = User::find($id);
        $user->status = $request->status;  
        $user->save();
        return redirect()->route('admin.member.index')->with('status','Member successfully inactive!!');
    }

    public function getMembersUrl($name)
    {
        $members = User::where([
            ['role_id', '=', 2],
            ['name', 'LIKE', "%$name%"],
        ])->orderBy('id', 'desc')->paginate(10);
        if(count($members) > 1) {
            $roles = Role::all();
            $permissions = Permission::all();
            return view('backend.member.index', compact('members', 'roles', 'permissions'));
        }else {
            $user = $members->first();
            return view('backend.member.show', compact('user'));
        }

    }
}
