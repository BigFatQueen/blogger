<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
use App\Http\Resources\UserResource;

class UserInfoBasicResource extends JsonResource
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
            'user' => new UserBasicResource(User::find($this->user_id)),
            'profile_image' => $this->profile_image
        ];
    }
}
