<?php

namespace Domain\Pusher\Events;


use Domain\Pusher\DTOS\Message;

class DestroyMessageEvent
{
    /**
     * Тело сообщения
     * @var string
     */
    protected $message;

    /**
     * Список индентификаторов получателей
     * @var array
     */
    protected $usersListIds = [];

    /**
     * Create a new event instance.
     * @param string $body
     * @param array $ids
     * @return void
     */
    public function __construct($message, array $ids)
    {
        $this->message = $message;
        $this->usersListIds = $ids;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getUsersListIds(): array
    {
        return $this->usersListIds;
    }
}
