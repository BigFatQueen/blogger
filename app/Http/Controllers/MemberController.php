<?php
namespace App\Http\Controllers;

use App\Creator;
use Illuminate\Http\Request;
use App\Helper\Log;
use App\Helper\UserHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
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
        $roles = Role::orderBy('id', 'desc')->get();
        $permissions = Permission::orderBy('id', 'desc')->get();
        $user_permissions = [];
        foreach ($user->permissions as $value) {
            array_push($user_permissions, $value->name);
        }
        return view('backend.member.edit',compact('user' ,'roles', 'permissions', 'user_permissions'));
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

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->change_pwd) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

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
