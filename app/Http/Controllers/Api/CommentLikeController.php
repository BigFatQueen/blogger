<?php

namespace App\Http\Controllers\Api;

use App\Content;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentLikeResource;
use App\CommentLike;
use Illuminate\Http\Request;
use Auth;

class CommentLikeController extends Controller
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
            'comment_id' => 'required|string|max:11',
        ]);
        $comment_Like = CommentLike::create([
            'comment_id' => $request->comment_id,
            'user_info_id' => Auth::user()->userInfo->id,
        ]); 
        $comment_Like =  CommentLikeResource::make($comment_Like);

        return response()->json([
            'success' => true,
            'data' => $comment_Like
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
        // $content = Content::find($id);
        // $content =  CommentResource::collection($content->comments);
        // return response()->json([
        //     'success'=> true,
        //     'data'=> $content
        // ],200);
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
            'comment_id' => 'required|string|max:11',
        ]);
        $comment_Like = CommentLike::find($id);
        $comment_Like->comment_id = $request->comment_id;
        $comment_Like->user_info_id = Auth::user()->userInfo->id;
        $comment_Like->save();
        
        $comment_Like =  CommentLikeResource::make($comment_Like);

        return response()->json([
            'success' => true,
            'data' => $comment_Like
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
        $comment_Like = CommentLike::find($id);
        $comment_Like->delete();

        return response()->json([
            'success'=> true,
            'data'=> [
                    'code' => 200,
                    'message' => "Comment Like Successfully Remove!!"
                ]
            ],200);
    }
}