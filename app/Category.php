<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $fillable = [
        'name'
    ];

    public function contents()
    {
        return $this->hasMany(Content::class);
    }
    public function creators()
    {
        return $this->belongsToMany(Creator::class);
    }
}
