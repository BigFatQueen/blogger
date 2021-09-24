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
            'level' => 'required|string|max:30',
            'price' => 'required|max:11',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'description' => "max:255"
        ]);
        
        $creator_id = Auth::user()->userInfo->creator->id;
        $main ="public/creators";
        $image_folder = "$main/$creator_id/plan_images/";
        $image_url = "creators/$creator_id/plan_images/";

        if ($request->file(['image'])) {
            $image = $request->file(['image']);
            $image_name = date('Y-m-d H-m').$image->getClientOriginalName();
            $image_path_url = $image_url.$image_name;
            $image->storeAs("$image_folder", $image_name);
        } else {
            $image_path_url = NULL;
        }

        $subscription_plan = SubscriptionPlan::create([
            'creator_id' => Auth::user()->userInfo->creator->id,
            'level' => $request->level,
            'price' => $request->price,
            'image' => $image_path_url,
            'description' => $request->description,
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
            'level' => 'required|string|max:30',
            'price' => 'required|max:11',
            'image' => 'required',
            'description' => "max:255"
        ]);

        $creator_id = Auth::user()->userInfo->creator->id;
        $main ="public/creators";
        $image_folder = "$main/$creator_id/plan_images/";
        $image_url = "creators/$creator_id/plan_images/";

        if ($request->file(['image'])) {
            $image = $request->file(['image']);
            $image_name = date('Y-m-d H-m').$image->getClientOriginalName();
            $image_path_url = $image_url.$image_name;
            $image->storeAs("$image_folder", $image_name);
        } else {
            $image_path_url = $request->image;
        }

        $subscription_plan = SubscriptionPlan::find($id);
        $subscription_plan->creator_id = Auth::user()->userInfo->creator->id;
        $subscription_plan->level = $request->level;
        $subscription_plan->price = $request->price;
        $subscription_plan->image = $image_path_url;
        $subscription_plan->description = $request->description;
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
