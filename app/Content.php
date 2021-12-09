<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'creator_id', 'category_id', 'title', 'content', 'audio', 'video', 'image', 'link', 'embed_url', 'status'
    ];
    
    public function creator()
    {
        return $this->belongsTo(Creator::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subscriptionPlans()
    {
        return $this->belongsToMany(SubscriptionPlan::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('id', 'desc');
    }

    public function pollOptions()
    {
        return $this->hasMany(PollOption::class);
    }
}
