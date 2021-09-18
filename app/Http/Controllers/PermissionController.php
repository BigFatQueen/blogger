<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {   
        $permissions = Permission::orderBy('id', 'desc')->get();
        return view('backend.permission.index',compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        return view('backend.permission.create');
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
            "name"=> 'required|min:3|max:50|unique:permissions',                      
        ]);
        $permission = Permission::create([
                'name' => $request->name,
                'guard_name' => 'web'
        ]);

        $log = new Log();
        $log->setOpt("Permission Log", Auth::user()->id, "Admin");
        $log->setReq('All', "Permission", "Store", json_encode($permission));
        $log->store();

        return redirect()->route('admin.permission.index')->with('status','Permission was successfully added!!');
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
        $permission = Permission::where('id', $id)->first();
        return view('backend.permission.edit',compact('permission'));
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

        Permission::where('id', $id)->update(['name' => $request->name]);
        return redirect()->route('admin.permission.index')->with('status','Permission was successfully updated!!');
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
           Permission::where('id', $id)->delete();
            return redirect()->route('admin.permission.index')->with('status','Permission was successfully deleted!!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('admin.permission.index')->with('status','You can\'t delete this permission.This permission was used by others.');
        }
    }
}
