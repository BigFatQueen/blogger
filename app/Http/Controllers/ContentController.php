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
use App\UserInfo;
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
        $creators = UserInfo::join('users', 'user_infos.user_id', 'users.id')
        ->where('role_id', 2)->orderBy('user_infos.id', 'desc')->select('user_infos.*')->get();
        $content = Content::find($id);
        $content_subscription_plans = [];
        foreach ($content->subscriptionPlans as $value) {
            array_push($content_subscription_plans, $value->id);
        }
        return view('backend.content.edit',compact('content' ,'categories', 'creators', 'content_subscription_plans'));
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
        $request->validate([
            'category_id' => 'required|max:20',
            'title' => 'required|string|max:255',
            'link' => 'max:255',
            'subscription_plan' => 'required',
        ]);
        
        $creator_id = $request->creator_id;
        $main ="public/creators";
        $audio_folder = "$main/$creator_id/audios/";
        $audio_url = "creators/$creator_id/audios/";

        $video_folder = "$main/$creator_id/videos/";
        $video_url = "creators/$creator_id/videos/";

        $image_folder = "$main/$creator_id/images/";
        $image_url = "creators/$creator_id/images/";

        if ($request->file(['audio'])) {
            $request->validate([
                'audio' => 'file|mimes:mp3,mpeg|max:1024',
            ]);
            $audio = $request->file(['audio']);
            $audio_name = date('Y-m-dH-m'). \uniqid();
            $audio_path_url = $audio_url.$audio_name;
            $audio->storeAs("$audio_folder", $audio_name);
        } else {
            $audio_path_url = $request->audio;
        }

        if ($request->file(['video'])) {
            $request->validate([
                'video' => 'file|mimes:mp4,3gp|max:20480',
            ]);
            $video = $request->file(['video']);
            $video_name = date('Y-m-dH-m'). \uniqid();
            $video_path_url = $video_url.$video_name;
            $video->storeAs("$video_folder", $video_name);
        } else {
            $video_path_url = $request->video;
        }

        if ($request->file(['image'])) {
            $request->validate([
                'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            ]);
            $images = $request->file('image');
            foreach($images as $image) {
                $image_name = date('Y-m-dH-m'). \uniqid();
                $image_path_url[] = $image_url.$image_name;
                $image->storeAs($image_folder, $image_name);
            }
            $image_path_url = json_encode($image_path_url);
        } else {
            $image_path_url = $request->image;
        }
        
        $id = \App\Helper\Crypt::crypt()->decrypt($id);
        $content = Content::find($id);
        $content->creator_id = $request->creator_id;
        $content->category_id = $request->category_id;
        $content->title = $request->title;
        $content->content = $request->content;
        $content->audio = $audio_path_url;
        $content->video = $video_path_url;
        $content->image = $image_path_url;
        $content->link = $request->link;
        $content->embed_url = $request->embed_url;
        $content->save();

        $content->subscriptionPlans()->sync($request->subscription_plan);

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
