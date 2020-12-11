<?php

namespace Domain\Pusher\Http\Resources\Message;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MessageCollection extends ResourceCollection
{

    /**
     * @var int
     */
    public $user_id;

    public function __construct(Collection $resource, $user_id)
    {
        $this->user_id = $user_id;
        parent::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'list' => $this->collection->map(function ($message) {
                return [
                    'id' => $message->id,
                    'userId' => $message->user->id,
                    'firstName' => $message->user->profile->first_name,
                    'lastName' => $message->user->profile->last_name,
                    'userPic' => $message->user->profile->user_pic,
                    'sex' => $message->user->profile->sex,
                    'body' => strip_tags($message->body, '<span><p>'),
                    'isMine' => ($message->user->id === $this->user_id),
                    'isRead' => $message->is_read,
                    'isEdited' => false,
                    'createdAt' => $message->created_at->timestamp,
                    'updatedAt' => $message->updated_at->timestamp,
                    'attachments' => new AttachmentsCollection($message->attachments),
                    'replyOn' => $message->parent ? new Message($message->parent, $this->user_id) : null,
                    'isForward' => $message->parent_chat_id ? true : false,
                ];
            })->reverse()->toArray()
        ];
    }
}
