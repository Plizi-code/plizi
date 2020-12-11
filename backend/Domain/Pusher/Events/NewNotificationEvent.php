<?php

namespace Domain\Pusher\Events;

class NewNotificationEvent
{
    /**
     * Тело сообщения
     * @var string
     */
    protected $notification;

    /**
     * Список индентификаторов получателей
     * @var array
     */
    protected $userId;

    /**
     * Create a new event instance.
     *
     * @param $notification
     * @param integer $id
     */
    public function __construct($notification, int $id)
    {
        $this->notification = $notification;
        $this->userId = $id;
    }

    /**
     * @return mixed
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @return array
     */
    public function getUserId(): array
    {
        return $this->userId;
    }
}
