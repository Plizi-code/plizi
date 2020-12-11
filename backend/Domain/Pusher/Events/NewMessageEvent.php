<?php

namespace Domain\Pusher\Events;


use Domain\Pusher\DTOS\Message;

class NewMessageEvent
{
    /**
     * Тело сообщения
     * @var string
     */
    protected $body;

    /**
     * @var
     */
    protected $attachments;

    /**
     * Список индентификаторов получателей
     * @var int
     */
    protected $author_id;

    /**
     * @var
     */
    protected $chat_id;

    /**
     * @var
     */
    protected $parent_id;

    /**
     * @var
     */
    protected $parent_chat_id;

    /**
     * @var
     */
    protected $toUserId;

    /**
     * NewMessageEvent constructor.
     *
     * @param $body
     * @param $author_id
     * @param $chat_id
     * @param $attachments
     * @param $parent_id
     * @param $parent_chat_id
     * @param $toUserId
     */
    public function __construct($body, $author_id, $chat_id, $attachments, $parent_id = null, $parent_chat_id = null, $toUserId = null)
    {
        $this->body = $body;
        $this->author_id = $author_id;
        $this->chat_id = $chat_id;
        $this->parent_id = $parent_id;
        $this->parent_chat_id = $parent_chat_id;
        $this->attachments = $attachments;
        $this->toUserId = $toUserId;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return int
     */
    public function getAuthorId()
    {
        return $this->author_id;
    }

    /**
     * @return mixed
     */
    public function getChatId()
    {
        return $this->chat_id;
    }

    /**
     * @return mixed
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @return mixed
     */
    public function getParentId()
    {
        return $this->parent_id ?: null;
    }

    /**
     * @return mixed
     */
    public function getParentChatId()
    {
        return $this->parent_chat_id ?: null;
    }

    /**
     * @return mixed
     */
    public function getToUserId()
    {
        return $this->toUserId;
    }
}
