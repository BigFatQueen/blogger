<?php

namespace App\Http\Resources;

use App\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return[
            'id' => $this->id,
            'category' => new CategoryResource(Category::find($this->category_id)),
            'name' => $this->name,
            'text' => $this->text,
            'audio' => $this->audio,
            'video' => $this->video,
            'image' => $this->image,
            'link' => $this->link,
            'embed_url' => $this->embed_url,
        ];
    }
}
