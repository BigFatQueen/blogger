<?php

namespace App\Http\Resources;

use App\UserInfo;
use Illuminate\Http\Resources\Json\JsonResource;

class PollResource extends JsonResource
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
        return [
            'id' => $this->id,
            'content_id' => $this->content_id,
            'user_info' => new UserInfoResource(UserInfo::find($this->user_info_id)),
            'status' => $this->status
        ];
    }
}