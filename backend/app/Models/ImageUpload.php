<?php

namespace App\Models;

use App\Traits\Commentable;
use App\Traits\Likeable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Event;
use Spiritix\LadaCache\Database\LadaCacheTrait;
use Storage;

class ImageUpload extends Model
{
    use LadaCacheTrait, Likeable, Commentable;

    const TAG_PRIMARY = 'primary';
    const TAG_SECONDARY = 'secondary';

    protected $fillable = [
        'original_name', 'path', 'url', 'user_id', 'size', 'tag', 'mime_type',
        'image_original_width',
        'image_original_height',
        'image_normal_path',
        'image_normal_width',
        'image_normal_height',
        'image_medium_path',
        'image_medium_width',
        'image_medium_height',
        'image_thumb_path',
        'image_thumb_width',
        'image_thumb_height',
        'like',
        'creatable_id',
        'creatable_type',
    ];

    public function getS3UrlAttribute()
    {
        return Storage::disk('s3')->url($this->image_thumb_path);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function boot()
    {
        parent::boot();
        static::created(static function($image) {
            if($image->tag === self::TAG_PRIMARY) {
                $affected = (new ImageUpload)
                    ->where('user_id', auth()->user()->id)
                    ->where('tag', self::TAG_PRIMARY)
                    ->where('id', '!=', $image->id)
                    ->update(['tag' => self::TAG_SECONDARY]);
                /**
                 * @todo For event
                 */
                Profile::where('user_id', auth()->id())->update(['user_pic' => $image->url]);
                Event::dispatch($affected ? 'user.profile.image.updated' : 'user.profile.image.created', ['user_id' => auth()->id()]);
            }
        });
        static::creating(function ($image) {
            $image->user_id = auth()->user()->id;
            $image->url = $image->s3Url;
        });
    }

    public function getDateFormat()
    {
        return 'U';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function creatable() {
        return $this->morphTo();
    }

    public function albums()
    {
        return $this->belongsToMany(PhotoAlbum::class);
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
        )->where('likeable_type', ImageUpload::class);
    }

    public function deleteDublicatesForAvatar()
    {
        /** @var Builder $query */
        $query = self::where([
            'original_name' => $this->original_name,
            'size' => $this->size,
            'tag' => 'secondary',
            'mime_type' => $this->mime_type,
            'image_original_width' => $this->image_original_width,
            'image_original_height' => $this->image_original_height,
        ]);
            return $query
                ->where('id', '!=', $this->id)
                ->leftJoin('image_upload_photo_album', 'image_uploads.id', '=', 'image_upload_photo_album.image_upload_id')
                ->whereNull('image_upload_id')
                ->delete();

    }
}
