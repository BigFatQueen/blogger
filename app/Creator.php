<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Creator extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'user_info_id', 'description'
    ];

    public function userInfo()
    {
        return $this->belongsTo(UserInfo::class);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    public function subscriptionPlans()
    {
        return $this->hasMany(SubscriptionPlan::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
    public function contents()
    {
        return $this->hasMany(Content::class);
    }
}
