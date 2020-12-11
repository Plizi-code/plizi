<?php


namespace Domain\Pusher\Models;

use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class ChatMessage
 * @package Domain\Pusher\Models
 */
class Chat extends Model
{

    use SoftDeletes;

    /**
     * @var string
     */
    protected $connection = 'mongodb';

    protected $casts = [
        'last_message_time' => 'datetime'
    ];

    /**
     * @var array
     */
    protected $dates = ['deleted_at', 'created_at', 'updated_at', 'last_message_time'];

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'last_message_body', 'last_user_id', 'last_message_time', 'updated_at', 'name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attendees()
    {
        return $this->belongsToMany(
            User::class, null, 'chat_ids', 'user_ids'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }
}
