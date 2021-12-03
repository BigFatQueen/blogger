<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    //
    protected $guarded = [];
    public function content(){
        $this->belongsTo(Content::class);
    }
}
