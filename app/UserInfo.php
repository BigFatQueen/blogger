<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    //
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function creator()
    {
        return $this->hasOne(Creator::class);
    }

    public function socials()
    {
        return $this->hasMany(UserInfoSocialLink::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
    
}
