<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Http\Resources\SubscriptionBasicResource;
use App\Subscription;
use App\Creator;
use App\Filter;
use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Contracts\Support\Jsonable;

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
            $subscribers = Subscription::where('creator_id', Auth::user()->userInfo->creator->id)->get();
        }else {
            $subscribers = Subscription::where('user_info_id', Auth::user()->userInfo->id)->get();
        }
        $subscribers =  SubscriptionResource::collection($subscribers);
        return response()->json([
            'success'=> true,
            'data'=> $subscribers
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
            'user_info_id' => Auth::user()->userInfo->id,
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

        $request->validate([
            'creator_id' => 'required|max:20',
            'subscription_plan_id' => 'required|max:20',
            'subscription_fee' => 'required|max:11',
            'fdate' => 'required',
            'tdate' => 'required'
        ]);

        $subscription = Subscription::find($id);
        $subscription->creator_id = $request->creator_id;
        $subscription->user_info_id = Auth::user()->userInfo->id;
        $subscription->subscription_plan_id = $request->subscription_plan_id;
        $subscription->subscription_fee = $request->subscription_fee;
        $subscription->fdate = $request->fdate;
        $subscription->tdate = $request->tdate;
        if($request->cdate != null) {
            $subscription->cdate = $request->cdate;
            $subscription->status = 2;
        }
        $subscription->save();

        $subscription = new SubscriptionResource($subscription);

        return response()->json([
            'success'=> true,
            'data'=> $subscription
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $cdate = $request->cdate;
        $subscription = Subscription::find($id);
        $subscription->cdate = $cdate;
        $subscription->status = 2;
        $subscription->save();
        $subscription->delete();

        return response()->json([
            'success'=> true,
            'data'=> [
                    'code' => 200,
                    'message' => "Subscription Successfully Delete!!"
                ]
            ],200);
    }

    public function creatorSubscriber()
    {
        $creator = Creator::find(Auth::user()->userinfo->creator->id);
        $subscribers =  SubscriptionResource::collection($subscribers);
        return response()->json([
            'success'=> true,
            'data'=> $subscribers
        ],200);
    }

    public function rsManager(Request $request)
    {
        $today = date('Y-m-d');
        $paginate_no = (int) $request->paginate_no;
        $type = $request->type;
        if($paginate_no == null) {
            $paginate_no = 10;
        }
        if($type == 'new') {
            $fdate = date("Y-m-d", \strtotime("-1 month"));
            $subscriptions = Subscription::where([
                ['creator_id', Auth::user()->userinfo->creator->id],
                ['tdate', ">=", $today],
                ['status', "=", 1],
                ['fdate', "<=", $fdate],
            ])->paginate($paginate_no);
        }elseif($type == 'cancelled') {
            $subscriptions = Subscription::where([
                ['creator_id', Auth::user()->userinfo->creator->id],
                ['status', "=", 2]
            ])->paginate($paginate_no);
        }elseif($type == 'keyword') {
            $keyword = $request->keyword;
            $subscriptions = Subscription::where([
                ['creator_id', Auth::user()->userinfo->creator->id],
                ['status', "=", 1],
            ])->whereHas('creator', function ($query) use ($keyword) {
                $query->whereHas('userInfo', function ($query) use ($keyword) {
                    $query->whereHas('user', function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', "%$keyword%" )
                        ->orWhere('email', 'LIKE', "%$keyword%" );
                    });
                });
            })->paginate($paginate_no);

        }else if($type == 'filter') {
            $filter_id = $request->filter_id;
            $filter = Filter::find($filter_id);
            $status = $filter->status;
            $tiers = $filter->tiers;
            $this_week = $filter->this_week;
            $last_week = $filter->last_week;
            $this_month = $filter->this_month;
            $last_month = $filter->last_month;
            $fdate = $filter->fdate;
            $tdate = $filter->tdate;

            $qcenters = DB::table('qcenters')
            ->join('qcenter_types', 'qcenters.qcenter_type_id', '=', 'qcenter_types.id')
            ->join('regions', 'qcenters.region_id', '=', 'regions.id')
            ->join('districts', 'qcenters.district_id', '=', 'districts.id')
            ->join('townships', 'qcenters.township_id', '=', 'townships.id')
            ->where([
                ['qcenters.qcenter_name', 'like', "%$keyword%"],
                ['qcenters.qcenter_id', '=', $user_qcenter],
                ['qcenters.del_status', '=', 0]
            ])
            ->orWhere([
                ['qcenters.phone', 'like', "%$keyword%"],
                ['qcenters.district_id', '=', $user_qcenter],
                ['qcenters.del_status', '=', 0]
            ]);

            $query = Subscription::where('creator_id', Auth::user()->userinfo->creator->id);
            // $query->where('status', 1);
            if ($status != null) {
                $status_arr = \json_decode($status);

                if (count($status_arr) > 0) {
                    foreach ($status_arr as $key => $status) {
                        if ($key == 0) {
                            $query->where('status', $status);
                        }else {
                            $query->orWhere('status', $status);
                        }
                    }
                }
            }

            if ($tiers != null) {
                $tiers = \json_decode($tiers);

                if (count($tiers) > 0) {
                    foreach ($tiers as $key => $tier) {
                        if ($key == 0) {
                            $query->where('subscription_plan_id', $tier);
                        }else {
                            $query->orWhere('subscription_plan_id', $tier);
                        }
                    }
                }
            }

            if ($this_week != null) {
                $fdate = date("Y-m-d", \strtotime("-1 week"));
                $tdate = date("Y-m-d");
                $query->where([
                    ['fdate', ">=", $fdate],
                    ['fdate', "<", $tdate],
                ]);
            }

            if ($last_week != null) {
                $fdate = date("Y-m-d", \strtotime("-2 week"));
                $tdate = date("Y-m-d", \strtotime("-1 week"));
                $query->where([
                    ['fdate', ">=", $fdate],
                    ['fdate', "<", $tdate],
                ]);
            }

            if ($this_month != null) {
                $fdate = date("Y-m-d", \strtotime("-1 month"));
                $tdate = date("Y-m-d");
                $query->where([
                    ['fdate', ">=", $fdate],
                    ['fdate', "<", $tdate],
                ]);
            }

            if ($last_month != null) {
                $fdate = date("Y-m-d", \strtotime("-2 month"));
                $tdate = date("Y-m-d", \strtotime("-1 month"));
                $query->where([
                    ['fdate', ">=", $fdate],
                    ['fdate', "<", $tdate],
                ]);
            }

            if ($fdate != null && $tdate != null) {
                $query->where([
                    ['fdate', ">=", $fdate],
                    ['fdate', "<", $tdate],
                ]);
            }

            $subscriptions = $query->paginate($paginate_no);
        }else {
            $subscriptions = Subscription::where([
                ['creator_id', Auth::user()->userinfo->creator->id],
                ['tdate', ">=", $today],
                ['status', "=", 1],
            ])->paginate($paginate_no);
        }
        $subscription_array = $subscriptions->toArray();
        $subscribers =  SubscriptionResource::collection($subscriptions);
        return response()->json([
            'success'=> true,
            'meta' => [
                'total' => $subscription_array['total'],
                'per_page' => $subscription_array['per_page']
            ],
            'data'=> $subscribers,
            'links' => [
                'first_page_url' => $subscription_array['first_page_url'],
                'last_page_url' => $subscription_array['last_page_url'],
                'next_page_url' => $subscription_array['next_page_url'],
                'prev_page_url' => $subscription_array['prev_page_url'],
            ],
        ],200);
    }

    public function earnings(Request $request)
    {
        $status = $request->status;
        if ($status == 1 || $status == 2) {
            if($status == 1){
                $fdate = date("Y-m-d", \strtotime("-6 month"));
                $tdate = date("Y-m-d");
            }else {

                $fdate = date("Y-m-d", \strtotime("-12 month"));
                $tdate = date("Y-m-d");
            }
            $subscriptions = Subscription::select('subscription_plan_id',DB::raw('sum(subscription_fee) as total'))
                ->where('creator_id', Auth::user()->userinfo->creator->id)
                ->where('fdate', '>=', $fdate)
                ->where('fdate', '<=', $tdate)
                ->groupBy('subscription_plan_id')
                ->get();
        }else {
            $subscriptions = Subscription::select('subscription_plan_id',DB::raw('sum(subscription_fee) as total'))
                ->where('creator_id', Auth::user()->userinfo->creator->id)
                ->groupBy('subscription_plan_id')
                ->get();
        }

        $subscriptions =  SubscriptionBasicResource::collection($subscriptions);
        return response()->json([
            'success'=> true,
            'data'=> $subscriptions
        ],200);
    }
}
