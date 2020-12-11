<?php


namespace Domain\Pusher\DTOs;


/**
 * Class Message
 * Data Transfer Object
 * @package Domain\Pusher\Dto
 */
class Message
{
    /**
     * @var int $id
     */
    public $id;

    /**
     * @var string $firstName
     */
    public $firstName;

    /**
     * @var string $lastName
     */
    public $lastName;
    /**
     * @var string $body
     */
    public $body;

    /**
     * @var int $createdAt
     */
    public $createdAt;

    /**
     * @var int $updatedAt
     */
    public $updatedAt;

    /**
     * @var bool $isEdited
     */
    public $isEdited = false;

    /**
     * @var bool $isRead
     */
    public $isRead = false;

    /**
     * @var bool $isMine
     */
    public $isMine = false;

    /**
     * @var string
     */
    public $userPic;

    /**
     * @var int $chatId
     */
    public $chatId;
}
