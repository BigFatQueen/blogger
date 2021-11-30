<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
use App\Region;
use App\Http\Resources\UserResource;

class UserInfoResource extends JsonResource
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
            'region' => new RegionResource(Region::find($this->region_id)),
            'address' => $this->address,
            'user' => new UserResource(User::find($this->user_id)),
            'phone_no' => $this->phone_no,
            'gender' => $this->gender,
            'dob' => $this->dob,
            'cover_photo' => $this->cover_photo,
            'profile_image' => $this->profile_image,
            'bio' => $this->bio,
            'socials' => UserInfoSocialLinkResource::collection($this->socials),
        ];
    }
}
