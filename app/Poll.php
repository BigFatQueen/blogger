<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    //
    protected $guarded = [];
    public function pollOption(){
        $this->belongsTo(PollOption::class);
    }
}
