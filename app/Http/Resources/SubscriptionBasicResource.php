<?php

namespace App\Http\Resources;

use App\Creator;
use App\Subscription;
use App\SubscriptionPlan;
use App\UserInfo;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionBasicResource extends JsonResource
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
            'subscription_plan' => new SubscriptionPlanBasicResource(SubscriptionPlan::find($this->subscription_plan_id)),
            'total' => $this->total
        ];
    }
}
