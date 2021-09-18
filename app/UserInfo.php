<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    //
    protected $fillable = [
        'user_id', 'phone_no', 'dob', 'cover_photo', 'profile_image', 'embed_url'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function creator()
    {
        return $this->hasOne(Creator::class);
    }
}
