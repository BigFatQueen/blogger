<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    //
    protected $fillable = [
        'creator_id', 'category_id', 'name', 'text', 'audio', 'video', 'image', 'link', 'embed_url', 'status'
    ];
    
    public function creator()
    {
        return $this->belongsTo(Creator::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
