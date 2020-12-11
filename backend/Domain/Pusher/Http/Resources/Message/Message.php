<?php

namespace Domain\Pusher\Http\Resources\Message;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class Message extends JsonResource
{

    /**
     * @var int
     */
    public $userId;

    public function __construct($resource, $user_id)
    {
        $this->userId = $user_id;
        parent::__construct($resource);
    }

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
            'firstName' => Arr::get($this, 'user.profile.first_name'),
            'lastName' => Arr::get($this, 'user.profile.last_name'),
            'userPic' => Arr::get($this, 'user.profile.user_pic'),
            'userId' => Arr::get($this, 'user.id'),
            'chatId' => $this->chat_id,
            'sex' => Arr::get($this, 'user.profile.sex'),
            'body' => strip_tags($this->body, '<span><p>'),
            'isMine' => (Arr::get($this, 'user.id') == $this->userId),
            'isRead' => $this->is_read,
            'isEdited' => false,
            'createdAt' => $this->created_at->timestamp,
            'updatedAt' => $this->updated_at->timestamp,
            'replyOn' => $this->parent ? new Message($this->parent, $this->userId) : null,
            'attachments' => new AttachmentsCollection($this->attachments),
        ];
    }
}
