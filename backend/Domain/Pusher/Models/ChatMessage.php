<?php


namespace Domain\Pusher\Models;

use App\Models\Profile;
use Carbon\Carbon;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

/**
 * Class ChatMessage
 * @package Domain\Pusher\Models
 */
class ChatMessage extends Model
{

    use SoftDeletes;

    /**
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * @var array
     */
    protected $fillable = [
        'chat_id',
        'body',
        'user_id',
        'parent_id',
        'parent_chat_id',
    ];

    /**
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent() {
        return $this->belongsTo(self::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chat() {
        return $this->belongsTo(Chat::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments() {
        return $this->hasMany(\Domain\Pusher\Models\ChatMessageAttachment::class);
    }

    public static function boot()
    {
        parent::boot();
        static::created(function ($message) {
            /** @var Chat $chat */
            $chat = Chat::find($message->chat_id);
            $chat->last_message_body = $message->body;
            $chat->last_user_id = $message->user_id;
            $chat->last_message_time = $message->created_at;
            $chat->save();
        });
        static::deleted(function ($message) {
            $last_message = self::where('chat_id', $message->chat_id)->latest('created_at')->first();
            /** @var Chat $chat */
            $chat = Chat::find($message->chat_id);
            if($last_message) {
                $chat->last_user_id = $last_message->user_id;
                $chat->last_message_time = $last_message->created_at;
                $chat->last_message_time = $last_message->created_at;
            } else {
                $chat->last_message_body = '';
                $chat->last_user_id = '';
                $chat->last_message_time = '';
            }
            $chat->save();
        });
    }
}
