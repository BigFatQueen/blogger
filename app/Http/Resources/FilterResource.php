<?php

namespace App\Http\Resources;

use App\UserInfo;
use Illuminate\Http\Resources\Json\JsonResource;

class FilterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'status' => \json_decode($this->status),
            'tiers' => \json_decode($this->tiers),
            'this_week' => $this->this_week,
            'last_week' => $this->last_week,
            'this_month' => $this->this_month,
            'last_month' => $this->last_month,
            'from_date' => $this->fdate,
            'to_date' => $this->tdate,
        ];
    }
}
