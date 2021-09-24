<?php

namespace App\Http\Controllers\Api;

use App\Content;
use App\Http\Controllers\Controller;
use App\Http\Resources\LikeResource;
use App\Like;
use Illuminate\Http\Request;
use Auth;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'content_id' => 'required|integer|max:11'
        ]);
        $like = Like::create([
            'content_id' => $request->content_id,
            'user_info_id' => Auth::user()->userInfo->id
        ]); 
        $like =  LikeResource::make($like);
    
        return response()->json([
            'success' => true,
            'data' => $like
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
        $content = Content::find($id);
        $like =  LikeResource::collection($content->likes);
        return response()->json([
            'success'=> true,
            'data'=> $like
        ],200);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $like = Like::find($id);
        $like->delete();

        return response()->json([
            'success'=> true,
            'data'=> [
                    'code' => 200,
                    'message' => "Like Successfully Remove!!"
                ]
            ],200);
    }
}