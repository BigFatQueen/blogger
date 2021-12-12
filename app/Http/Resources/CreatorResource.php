<?php

namespace App\Http\Resources;

use App\Category;
use Illuminate\Http\Resources\Json\JsonResource;
use App\UserInfo;
use App\Region;
use App\Http\Resources\UserInfoResource;
use App\Http\Resources\CategoryResource;

class CreatorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        // dd($this->subscriptions);
        $counts = 0;
        foreach ($this->subscriptions as $subscription) {
            $user_info_id = session("key-$subscription->user_info_id");
            echo $user_info_id;
            echo $subscription->user_info_id;
            if($user_info_id != $subscription->user_info_id){
                $counts +=1;
            }
            session(["key-$subscription->user_info_id" => $subscription->user_info_id]);
        }
        return[
            'id' => $this->id,
            'user_info' => new UserInfoResource(UserInfo::find($this->user_info_id)),
            'description' => $this->description,
            'categories' => CategoryResource::collection($this->categories),
            'subscription_plans' => SubscriptionPlanResource::collection($this->subscriptionPlans),
            'subscriptions' => SubscriptionResource::collection($this->subscriptions),
            'subscriptions_counts' => $counts,
            'content_counts' => count($this->contents),
        ];
    }
}
