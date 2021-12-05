<?php

namespace App\Http\Controllers\Api;

use App\Content;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContentResource;
use App\Http\Resources\PollOptionResource;
use App\PollOption;
use Illuminate\Http\Request;
use Auth;

class PollOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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
            'category_id' => 'required|integer|max:11',
            'title' => 'required|max:255',
            'name' => 'required|max:255',
            'subscription_plan' => 'required',
        ]);

        $content = Content::create([
            'creator_id' => Auth::user()->userInfo->creator->id,
            'category_id' => $request->category_id,
            'title' => $request->title
        ]); 


        $content->subscriptionPlans()->sync(json_decode($request->subscription_plan));

        $content = new ContentResource($content);

        $poll_option = PollOption::create([
            'content_id' => $content->id,
            'name' => $request->name
        ]); 

        $poll_option =  PollOptionResource::make($poll_option);

        return response()->json([
            'success' => true,
            'data' => $poll_option
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
        $poll_options = PollOption::where('content_id', $id)->get();
        $poll_options = PollOptionResource::collection($poll_options);
        return response()->json([
            'success'=> true,
            'data'=> $poll_options
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
            'name' => 'required|max:255',
        ]);
        $poll_option = PollOption::find($id);
        $poll_option->name = $request->name;
        $poll_option->save();
        
        $poll_option =  PollOptionResource::make($poll_option);

        return response()->json([
            'success' => true,
            'data' => $poll_option
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
        $poll_option = PollOption::find($id);
        $poll_option->delete();

        return response()->json([
            'success'=> true,
            'data'=> [
                    'code' => 200,
                    'message' => "Poll Option Successfully Delete!!"
                ]
            ],200);
    }
}