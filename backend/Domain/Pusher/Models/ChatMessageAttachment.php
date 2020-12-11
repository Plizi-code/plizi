<?php


namespace Domain\Pusher\Models;

use App\Models\User;
use Jenssegers\Mongodb\Eloquent\Model;
use Storage;

/**
 * Class ChatMessageAttachment
 * @package Domain\Pusher\Models
 */
class ChatMessageAttachment extends Model
{
    protected $connection = 'mongodb';

    protected $fillable = [
        'original_name',
        'path',
        'url',
        'user_id',
        'size',
        'tag',
        'mime_type',
        'image_normal_path',
        'image_medium_path',
        'image_thumb_path',
        'image_normal_width',
        'image_normal_height',
        'image_thumb_width',
        'image_thumb_height',
        'image_medium_width',
        'image_medium_height',
        'image_original_width',
        'image_original_height',
    ];
}
