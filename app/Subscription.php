<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    //
    protected $guarded = [];
    
    public function creator()
    {
        return $this->belongsTo(Creator::class);
    }
}
