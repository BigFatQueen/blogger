<?php
namespace App\Http\Controllers;

use App\Creator;
use Illuminate\Http\Request;
use App\Helper\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
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
        $users = User::orderBy('id', 'desc')->paginate(10);
        if ($request->ajax()) {
            if ($request->keyword == 'online') {
                $status = 1;
            }
            return view('backend.user.table', compact('users', 'status'))->render();
        }else {
            return view('backend.user.index', compact('users', 'status'));
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
        return view('backend.user.edit',compact('user' ,'roles', 'permissions', 'user_permissions'));
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
