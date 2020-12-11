<?php

namespace Domain\Pusher\Http\Resources\Chat;

use App\Http\Resources\Community\Community;
use App\Http\Resources\User\User;
use App\Models\User as UserModel;
use App\Models\Community as CommunityModel;
use Domain\Pusher\Http\Resources\Chat\AttendeesCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class Chat extends JsonResource
{

    /**
     * @var int
     */
    public $userId;

    public function __construct($resource, $user_id = null)
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
            'name' => $this->name,
            'lastMessageText' => $this->last_message_body,
            'lastMessageDT' => $this->last_message_time ? $this->last_message_time->timestamp : null,
            'isRead' => (bool)$this->last_is_read,
            'isLastFromMe' => ($this->userId == $this->last_user_id),
            'attendees' => new AttendeesCollection($this->attendees, $this->user_id)
        ];
    }
}
