<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Subscription;
use Illuminate\Http\Request;
use Auth;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $role = Auth::user()->role->name;
        if ($role == 'creator') {
            $subscriptions = Subscription::where('creator_id', Auth::user()->userInfo->creator->id)->get();
        }else {
            $subscriptions = Subscription::where('user_info_id', Auth::user()->userInfo->id)->get();
        }
        $subscriptions =  SubscriptionResource::collection($subscriptions);
        return response()->json([
            'success'=> true,
            'data'=> $subscriptions
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
            'creator_id' => 'required|max:20',
            'subscription_plan_id' => 'required|max:20',
            'subscription_fee' => 'required|max:11',
            'fdate' => 'required',
            'tdate' => 'required'
        ]);

        $subscription = Subscription::create([
            'creator_id' => $request->creator_id,
            'user_info_id' => Auth::user()->userInfo->creator->id,
            'subscription_plan_id' => $request->subscription_plan_id,
            'subscription_fee' => $request->subscription_fee,
            'fdate' => $request->fdate,
            'tdate' => $request->tdate
        ]);

        $subscription = new SubscriptionResource($subscription);

        return response()->json([
            'success'=> true,
            'data'=> $subscription
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
        $subscription = Subscription::find($id);
        $subscription =  SubscriptionResource::make($subscription);
        return response()->json([
            'success'=> true,
            'data'=> $subscription
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
        //
    }
}
