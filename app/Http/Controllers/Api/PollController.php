<?php

namespace App\Http\Controllers\Api;

use App\Content;
use App\Http\Controllers\Controller;
use App\Http\Resources\PollResource;
use App\Poll;
use Illuminate\Http\Request;
use Auth;

class PollController extends Controller
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
            'content_id' => 'required|integer|max:11',
            'status' => 'required|integer|max:11'
        ]);
        $poll = Poll::create([
            'content_id' => $request->content_id,
            'user_info_id' => Auth::user()->userInfo->id,
            'status' => $request->status
        ]); 
        $poll =  PollResource::make($poll);

        return response()->json([
            'success' => true,
            'data' => $poll
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
        $content =  PollResource::collection($content->polls);
        return response()->json([
            'success'=> true,
            'data'=> $content
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
        $request->validate([
            'content_id' => 'required|integer|max:11',
            'status' => 'required|integer|max:11'
        ]);
        $poll = Poll::find($id);
        $poll->content_id = $request->content_id;
        $poll->user_info_id = Auth::user()->userInfo->id;
        $poll->status = $request->status;
        $poll->save();
        
        $poll =  PollResource::make($poll);

        return response()->json([
            'success' => true,
            'data' => $poll
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
        $poll = Poll::find($id);
        $poll->delete();

        return response()->json([
            'success'=> true,
            'data'=> [
                    'code' => 200,
                    'message' => "Poll Successfully Remove!!"
                ]
            ],200);
    }
}