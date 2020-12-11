<?php

namespace Domain\Pusher\Events;


use App\Models\User;
use Domain\Pusher\Repositories\ChatRepository;
use App\Http\Resources\User\SimpleUser;
use Illuminate\Queue\SerializesModels;

class ChatActionEvent
{

    /**
     * @var
     */
    protected $userIds;

    /**
     * @var
     */
    protected $data;

    protected $action;

    /**
     * ChatActionEvent constructor.
     * @param array $userIds
     * @param string $action
     * @param $data
     */
    public function __construct(array $userIds, string $action, $data)
    {
        $this->userIds = $userIds;
        $this->data = $data;
        $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getUserIds(): array
    {
        return $this->userIds;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}
