<?php
namespace App\Http\Controllers;

use App\Category;
use App\Content;
use App\Creator;
use App\Helper\ContentHelper;
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

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) 
    {   
        $status = null;
        $creators = Creator::all();
        $categories = Category::all();
        $contents = Content::orderBy('id', 'desc')->paginate(10);
        if ($request->ajax()) {
            
            $filter_arr = [];
            $creator_id = $request->creator_id;
            $category_id = $request->category_id;
            $keyword = $request->keyword;

            $filter_arr['creator_id'] = $creator_id;
            $filter_arr['category_id'] = $category_id;
            $filter_arr['keyword'] = $keyword;
            $contents = ContentHelper::search($creator_id, $category_id, $keyword);
            return view('backend.content.table', compact('contents', 'status', 'filter_arr'))->render();
        }else {
            return view('backend.content.index', compact('contents', 'status', 'creators', 'categories'));
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
        $content = Content::find($id);
        return view('backend.content.show', compact('content'));

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
        $categories = Category::all();
        $content = Content::find($id);
        return view('backend.content.edit',compact('content' ,'categories'));
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
        return redirect()->route('admin.content.index')->with('status','Content was successfully updated!!');
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
        $content = Content::find($id);    
        $content->delete();
        return redirect()->route('admin.content.index')->with('status','Content was successfully deleted!!');
        
    }
    
    public function inActive(Request $request, $id)
    {
        $id = \App\Helper\Crypt::crypt()->decrypt($id);
        $content = Content::find($id);
        $content->status = $request->status;  
        $content->save();
        return redirect()->route('admin.content.index')->with('status','Content successfully inactive!!');
    }

    public function getcontentsUrl($name)
    {
        $contents = User::where([
            ['role_id', '=', 2],
            ['name', 'LIKE', "%$name%"],
        ])->orderBy('id', 'desc')->paginate(10);
        if(count($contents) > 1) {
            $roles = Role::all();
            $permissions = Permission::all();
            return view('backend.content.index', compact('contents', 'roles', 'permissions'));
        }else {
            $user = $contents->first();
            return view('backend.content.show', compact('user'));
        }

    }
}
