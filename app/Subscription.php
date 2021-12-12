<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    //
    use SoftDeletes;
    protected $guarded = [];
    
    public function creator()
    {
        return $this->belongsTo(Creator::class);
    }
}
