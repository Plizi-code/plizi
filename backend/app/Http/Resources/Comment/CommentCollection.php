<?php

namespace App\Http\Resources\Comment;

use App\Http\Resources\Post\AttachmentsCollection;
use App\Http\Resources\User\Image;
use App\Http\Resources\User\SimpleUser;
use App\Http\Resources\User\SimpleUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;

class CommentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'list' => $this->collection->map(function($comment) use ($request) {
                $data = [
                    'id' => $comment->id,
                    'body' => $comment->body,
                    'author' => new SimpleUser($comment->author),
                    'attachments' => new AttachmentsCollection($comment->attachments),
                    'thread' => $comment->children ? new CommentCollection($comment->children) : [],
                    'likes' => $comment->likes,
                    'usersLikes' => new SimpleUsers($comment->usersLikes),
                    'alreadyLiked' => (bool)count($comment->like),
                    'createdAt' => $comment->created_at,
                ];
                return $data;
            }),
        ];
    }
}
