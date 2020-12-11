<?php


namespace Domain\Pusher\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class ChatMessage
 * @package Domain\Pusher\Models
 */
class ChatMessageStatus extends Model
{
    protected $connection = 'mongodb';

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];
}
