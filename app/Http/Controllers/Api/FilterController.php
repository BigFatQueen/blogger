<?php

namespace App\Http\Controllers\Api;

use App\Content;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContentResource;
use App\Http\Resources\FilterResource;
use App\Http\Resources\FilterOptionResource;
use App\Filter;
use Illuminate\Http\Request;
use Auth;

class FilterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filters = Filter::where('creator_id', Auth::user()->userInfo->creator->id)->get();
        $filters =  FilterResource::collection($filters);
        return response()->json([
            'filters' => $filters,
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
        $array = [1,2];
        // dd(\json_encode($array));
        $request->validate([
            'status' => 'max:255',
            'tiers' => 'max:255',
            'this_week' => 'integer|max:11',
            'last_week' => 'integer|max:11',
            'this_month' => 'integer|max:11',
            'last_month' => 'integer|max:11',
        ]);
        $filter = Filter::create([
            'creator_id' => Auth::user()->userInfo->creator->id,
            'status' => \json_encode($request->status),
            'tiers' =>  \json_encode($request->tiers),
            'this_week' => $request->this_week,
            'last_week' => $request->last_week,
            'this_month' => $request->this_month,
            'last_month' => $request->last_month,
            'fdate' => $request->fdate,
            'tdate' => $request->tdate,
        ]); 
        $filter =  FilterResource::make($filter);

        return response()->json([
            'success' => true,
            'data' => $filter
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
            'status' => 'max:255',
            'tiers' => 'max:255',
            'this_week' => 'integer|max:11',
            'last_week' => 'integer|max:11',
            'this_month' => 'integer|max:11',
            'last_month' => 'integer|max:11',
        ]);

        $filter = Filter::find($id);
        $filter->status = \json_encode($request->$status);
        $filter->tiers =  \json_encode($request->tiers);
        $filter->this_week = $request->this_week;
        $filter->last_week = $request->last_week;
        $filter->this_month = $request->this_month;
        $filter->last_month = $request->last_month;
        $filter->fdate = $request->fdate;
        $filter->tdate = $request->tdate;
        $filter->save();
        
        $filter =  FilterResource::make($filter);

        return response()->json([
            'success' => true,
            'data' => $filter
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
        $filter = Filter::find($id);
        $filter->delete();

        return response()->json([
            'success'=> true,
            'data'=> [
                    'code' => 200,
                    'message' => "Filter Successfully Remove!!"
                ]
            ],200);
    }
}