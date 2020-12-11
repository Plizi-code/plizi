<?php

namespace App\Models;

use Domain\Pusher\Models\ChatMessage;
use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{


    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    protected $fillable = [
        'user_id', 'post_id'
    ];

    /**
     * @return string
     */
    public function getDateFormat()
    {
        return 'U';
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function($like) {
            $like->created_at = time();
            $like->updated_at = time();
        });
        static::created(function($like) {
            Post::where('id', $like->post_id)->increment('likes');
        });
        static::deleting(function($like) {
            Post::where('id', $like->post_id)->decrement('likes');
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
