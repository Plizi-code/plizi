<?php

namespace App\Models;

use App\Traits\Likeable;
use Illuminate\Database\Eloquent\Model;
use Spiritix\LadaCache\Database\LadaCacheTrait;

class Comment extends Model
{
    use LadaCacheTrait, Likeable;

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    protected $fillable = [
        'body'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function author() {
        return $this->hasOne( User::class, 'id', 'author_id' );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable() {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments() {
        return $this->hasMany(CommentAttachment::class, 'comment_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function reply() {
        return $this->hasOne( self::class, 'id', 'reply_on');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'reply_on', 'id');
    }

    public function getDateFormat() {
        return 'U';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function like() {
        return $this->morphMany(Like::class, 'likeable')
            ->where('user_id', \Auth::user()->id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function usersLikes()
    {
        return $this->hasManyThrough(
            User::class,
            Like::class,
            'likeable_id',
            'id',
            'id',
            'user_id'
        )->where('likeable_type', Comment::class);
    }
}
