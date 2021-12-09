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
        return[
            'id' => $this->id,
            'user_info' => new UserInfoResource(UserInfo::find($this->user_info_id)),
            'description' => $this->description,
            'categories' => CategoryResource::collection($this->categories),
            'subscription_plans' => SubscriptionPlanResource::collection($this->subscriptionPlans),
        ];
    }
}
