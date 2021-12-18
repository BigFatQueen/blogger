<?php

namespace App\Http\Controllers\Api;

use App\Content;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContentResource;
use App\Http\Resources\PollResource;
use App\Http\Resources\PollOptionResource;
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
            'poll_option_id' => 'required|integer|max:11'
        ]);
        $poll = Poll::create([
            'poll_option_id' => $request->poll_option_id,
            'user_info_id' => Auth::user()->userInfo->id
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
        $poll_votes = [];
        $check_votes = [];
        $content = Content::find($id);
        $poll_options = $content->pollOptions;
        foreach ($poll_options as $key => $poll_option) {
            $polls[$poll_option->id] = count(Poll::where('poll_option_id', $poll_option->id)->get());
        }
        $total_votes = array_sum($polls);

        foreach ($poll_options as $key => $poll_option) {
            $votes = count(Poll::where('poll_option_id', $poll_option->id)->get());
            if($votes) {
                $poll_votes[$poll_option->id] = $votes * (100 / $total_votes);
            }
        }

        foreach ($poll_options as $key => $poll_option) {
            $voted = Poll::where([
                ['poll_option_id', $poll_option->id],
                ['user_info_id', Auth::user()->userInfo->id],
                ])->get()->first();
            if($voted){
                $check_votes['poll_option_id'] = $poll_option->id;
            }
        }

        $content =  PollOptionResource::collection($content->pollOptions);
        return response()->json([
            'success'=> true,
            'data'=> [
                'result' => $poll_votes,
                'poll_options' => $content,
                'voted' => $check_votes
            ]
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
            'poll_option_id' => 'required|max:11',
        ]);
        $poll = Poll::find($id);
        $poll->poll_option_id = $request->poll_option_id;
        $poll->user_info_id = Auth::user()->userInfo->id;
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
        $poll = Poll::where('poll_option_id',$id)->first();

     
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