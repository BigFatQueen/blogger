<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $guarded = [];
    
    public function content(){
        return $this->belongsTo(Content::class);
    }

    public function commentReplies(){
        return $this->hasMany(CommentReply::class);
    }
}
