<?php

namespace App\Http\Resources;

use App\Creator;
use App\Subscription;
use App\SubscriptionPlan;
use App\UserInfo;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionCreatorResource extends JsonResource
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
            'creator' => new CreatorBasicResource(Creator::find($this->creator_id))
        ];
    }
}
