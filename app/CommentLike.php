<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    //
    protected $guarded = [];
    public function comment(){
        $this->belongsTo(Comment::class);
    }
}
