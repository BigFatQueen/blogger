<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionPlan extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'creator_id', 'level', 'price', 'image', 'description'
    ];
    public function creator()
    {
        return $this->belongsTo(Creator::class);
    }
}
