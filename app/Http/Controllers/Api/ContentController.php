<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Content;
use App\Http\Resources\ContentResource;
use Auth;
class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contents = Content::where('creator_id', Auth::user()->userInfo->creator->id)->get();
        $contents =  ContentResource::collection($contents);
        return response()->json([
            'success'=> true,
            'data'=> $contents
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'category_id' => 'required|max:20',
            'name' => 'required|string|max:255',
            'audio' => 'file|mimes:mp3,mpeg|max:1024', 
            'video' => 'file|mimes:mp4,3gp|max:10480', 
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'link' => 'max:255',
        ]);
        $creator_id = Auth::user()->userInfo->creator->id;
        $main ="public/creators";
        $audio_folder = "$main/$creator_id/audios/";
        $audio_url = "creators/$creator_id/audios/";

        $video_folder = "$main/$creator_id/videos/";
        $video_url = "creators/$creator_id/videos/";

        $image_folder = "$main/$creator_id/images/";
        $image_url = "creators/$creator_id/images/";

        if ($request->file(['audio'])) {
            $audio = $request->file(['audio']);
            $audio_name = date('Y-m-d H-m').$audio->getClientOriginalName();
            $audio_path_url = $audio_url.$audio_name;
            $audio->storeAs("$audio_folder", $audio_name);
        } else {
            $audio_path_url = NULL;
        }

        if ($request->file(['video'])) {
            $video = $request->file(['video']);
            $video_name = date('Y-m-d H-m').$video->getClientOriginalName();
            $video_path_url = $video_url.$video_name;
            $video->storeAs("$video_folder", $video_name);
        } else {
            $video_path_url = NULL;
        }

        if ($request->file(['image'])) {
            $images = $request->file('image');
            foreach($images as $image) {
                $image_name = date('Y-m-d H-m').$image->getClientOriginalName();
                $image_path_url[] = $image_url.$image_name;
                $image->storeAs($image_folder, $image_name);
            }
            $image_path_url = json_encode($image_path_url);
        } else {
            $image_path_url = NULL;
        }

        $content = Content::create([
            'creator_id' => Auth::user()->userInfo->creator->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'text' => $request->text,
            'audio' => $audio_path_url,
            'video' => $video_path_url,
            'image' => $image_path_url,
            'link' => $request->link,
            'embed_url' => $request->embed_url,
        ]);
        

        $content = new ContentResource($content);

        return response()->json([
            'success'=> true,
            'data'=> $content
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 
        $request->validate([
            'level' => ['required', 'string','max:30'],
            'price' => ['required', 'max:11']
        ]);
        $subscription_plan = Content::find($id);
        $subscription_plan->creator_id = Auth::user()->userInfo->creator->id;
        $subscription_plan->level = $request->level;
        $subscription_plan->price = $request->price;
        $subscription_plan->save();

        $subscription_plan = new ContentResource($subscription_plan);

        return response()->json([
            'success'=> true,
            'data'=> $subscription_plan
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subscription_plan = Content::find($id);
        $subscription_plan->delete();

        return response()->json([
            'success'=> true,
            'data'=> [
                    'code' => 200,
                    'message' => "Subscription Plan Successfully Delete!!"
                ]
            ],200);
    }
}
