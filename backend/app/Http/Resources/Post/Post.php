<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\Community\Community;
use App\Http\Resources\User\SimpleUser;
use App\Models\User as UserModel;
use App\Models\Community as CommunityModel;
use Illuminate\Http\Resources\Json\JsonResource;

class Post extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if(isset($this->postable) && $this->postable instanceof UserModel) {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'body' => strip_tags($this->body, '<span><p>'),
                'primaryImage' => $this->primary_image,
                'likes' => $this->likes,
                'views' => $this->views,
                'sharesCount' => $this->children_count,
                'commentsCount' => $this->comments_count,
                'alreadyLiked' => $this->alreadyLiked,
                'attachments' => new AttachmentsCollection($this->attachments),
                'user' => new SimpleUser($this->postable),
                'sharedFrom' => $this->parent_id && $this->parent->parent_id ? new Post($this->parent) : ($this->parent_id && !$this->parent->parent_id ? new PostWithoutParent($this->parent) : null),
                'createdAt' => $this->created_at,
                'author' => new SimpleUser($this->author),
            ];
        } else if(isset($this->postable) && $this->postable instanceof CommunityModel) {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'body' => strip_tags($this->body, '<span><p>'),
                'primaryImage' => $this->primary_image,
                'likes' => $this->likes,
                'views' => $this->views,
                'sharesCount' => $this->children_count,
                'commentsCount' => $this->comments_count,
                'alreadyLiked' => $this->alreadyLiked,
                'attachments' => new AttachmentsCollection($this->attachments),
                'community' => new Community($this->postable),
                'sharedFrom' => $this->parent_id && $this->parent->parent_id ? new Post($this->parent) : ($this->parent_id && !$this->parent->parent_id ? new PostWithoutParent($this->parent) : null),
                'createdAt' => $this->created_at,
                'author' => new SimpleUser($this->author),
            ];
        }
    }
}
