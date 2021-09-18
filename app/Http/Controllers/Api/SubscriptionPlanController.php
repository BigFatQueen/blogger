<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SubscriptionPlan;
use App\Http\Resources\SubscriptionPlanResource;
use Auth;
class SubscriptionPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscription_plans = SubscriptionPlan::where('creator_id', Auth::user()->userInfo->creator->id)->get();
        $subscription_plans =  SubscriptionPlanResource::collection($subscription_plans);
        return response()->json([
            'success'=> true,
            'data'=> $subscription_plans
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
            'level' => ['required', 'string','max:30', 'unique:subscription_plans'],
            'price' => ['required', 'max:11']
        ]);
        $subscription_plan = SubscriptionPlan::create([
            'creator_id' => Auth::user()->userInfo->creator->id,
            'level' => $request->level,
            'price' => $request->price
        ]);

        $subscription_plan = new SubscriptionPlanResource($subscription_plan);

        return response()->json([
            'success'=> true,
            'data'=> $subscription_plan
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
        $subscription_plan = SubscriptionPlan::find($id);
        $subscription_plan->creator_id = Auth::user()->userInfo->creator->id;
        $subscription_plan->level = $request->level;
        $subscription_plan->price = $request->price;
        $subscription_plan->save();

        $subscription_plan = new SubscriptionPlanResource($subscription_plan);

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
        $subscription_plan = SubscriptionPlan::find($id);
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
