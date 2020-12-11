<?php

namespace App\Http\Resources\Comment;

use App\Http\Resources\Post\AttachmentsCollection;
use App\Http\Resources\User\Image;
use App\Http\Resources\User\SimpleUser;
use App\Http\Resources\User\SimpleUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class Comment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'body' => $this->body,
            'author' => new SimpleUser($this->author),
            'attachments' => new AttachmentsCollection($this->attachments),
            'likes' => $this->likes,
            'usersLikes' => new SimpleUsers($this->usersLikes),
            'alreadyLiked' => (bool)count($this->like),
            'createdAt' => $this->created_at,
        ];

        return $data;
    }
}
