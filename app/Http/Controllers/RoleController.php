<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {   
        $roles = Role::orderBy('id', 'desc')->get();
        return view('backend.role.index',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {           
        $permissions = Permission::orderBy('id', 'desc')->get();
        return view('backend.role.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "name"=> 'required|min:3|max:50|unique:roles',                      
        ]);

        $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'web'
        ]);
        if ($request->permissions != null) {
            $role->syncPermissions($request->permissions);
        }
        $log = new Log();
        $log->setOpt("Role Log", Auth::user()->id, "Admin");
        $log->setReq('All', "Role", "Store", json_encode($role));
        $log->store();

        return redirect()->route('admin.role.index')->with('status','Role was successfully added!!');
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
        $role= Role::where('id', $id)->first();
        $permissions = Permission::orderBy('id', 'desc')->get();
        $user_permissions = [];
        foreach ($role->permissions as $value) {
            array_push($user_permissions, $value->name);
        }
        return view('backend.role.edit',compact('role', 'permissions', 'user_permissions'));
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

        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();

        $role->syncPermissions($request->permissions);

        return redirect()->route('admin.role.index')->with('status','Role was successfully updated!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Facility  $facility
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $id = \App\Helper\Crypt::crypt()->decrypt($id);
            DB::table('roles')->where('id', $id)->delete();
            return redirect()->route('admin.role.index')->with('status','Role was successfully deleted!!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('admin.role.index')->with('status','You can\'t delete this role.This role was used by others.');
        }
    }
}
