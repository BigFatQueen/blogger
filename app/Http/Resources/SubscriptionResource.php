<?php

namespace App\Http\Resources;

use App\Creator;
use App\Subscription;
use App\SubscriptionPlan;
use App\UserInfo;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
            'creator_id' => new CreatorBasicResource(Creator::find($this->creator_id)),
            'user_info_id' => new UserInfoBasicResource(UserInfo::find($this->user_info_id)),
            'subscription_plan_id' => new SubscriptionPlanResource(SubscriptionPlan::find($this->subscription_plan_id)),
            'subscription_fee' => $this->subscription_fee,
            'fdate' => $this->fdate,
            'tdate' => $this->tdate
        ];
    }
}
