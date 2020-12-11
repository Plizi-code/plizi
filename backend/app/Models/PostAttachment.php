<?php


namespace App\Models;

use App\Traits\Commentable;
use App\Traits\Likeable;
use Illuminate\Database\Eloquent\Model;
use Spiritix\LadaCache\Database\LadaCacheTrait;
use Storage;

/**
 * Class ChatMessageAttachment
 * @package Domain\Pusher\Models
 */
class PostAttachment extends Model
{
    use Likeable, LadaCacheTrait, Commentable;

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

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
        'like',
    ];

    /**
     * @return string
     */
    public function getS3UrlAttribute()
    {
        return Storage::disk('s3')->url($this->path);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function usersLikes()
    {
        return $this->hasManyThrough(
            User::class,
            Like::class,
            'likeable_id',
            'id',
            'id',
            'user_id'
        )->where('likeable_type', PostAttachment::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function like() {
        return $this->morphMany(Like::class, 'likeable')
            ->where('user_id', \Auth::user()->id);
    }

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
        static::creating(function ($file) {
            $file->user_id = auth()->user()->id;
        });
    }
}
