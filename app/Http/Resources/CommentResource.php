<?php

namespace App\Http\Resources;

use App\UserInfo;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content_id' => $this->content_id,
            'user_info' => new UserInfoBasicResource(UserInfo::find($this->user_info_id)),
            'comment' => $this->comment,
            'created_at' => $this->created_at,
            'comment_replies' => CommentReplyResource::collection($this->commentReplies),
            'comment_like_counts' => (count($this->commentLikes)),
            'comment_likes' => CommentLikeResource::collection($this->commentLikes),
        ];
    }
}
