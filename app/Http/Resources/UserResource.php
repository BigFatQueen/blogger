<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Role;
class UserResource extends JsonResource
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
            'role' => new RoleResource(Role::find($this->role_id)),
            'name' => $this->name,
            'email' => $this->email,
            'phone_no' => $this->phone_no,
            'address' => $this->address,
            'image' => $this->image
        ];
    }
}
