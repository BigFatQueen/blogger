<?php

namespace App\Http\Resources;

use App\Category;
use App\Creator;
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
        $likes = count($this->likes);
        return[
            'id' => $this->id,
            'category' => new CategoryResource(Category::find($this->category_id)),
            'subscription_plans' => SubscriptionPlanResource::collection($this->subscriptionPlans),
            'creator' => new CreatorResource(Creator::find($this->creator_id)),
            'like_counts' => $likes,
            'likes' => LikeResource::collection($this->likes),
            'comments' => CommentResource::collection($this->comments),
            'title' => $this->title,
            'content' => $this->content,
            'audio' => $this->audio,
            'video' => $this->video,
            'image' => $this->image,
            'link' => $this->link,
            'embed_url' => $this->embed_url,
            'created_at' => $this->created_at,
        ];
    }
}
