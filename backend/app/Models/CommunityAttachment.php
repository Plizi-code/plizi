<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spiritix\LadaCache\Database\LadaCacheTrait;
use Storage;

/**
 * App\Models\CommunityAttachment
 *
 * @property int $id
 * @property string $user_id
 * @property int|null $community_id
 * @property string $original_name
 * @property string $path
 * @property string $mime_type
 * @property int $size
 * @property int $updated_at
 * @property int $created_at
 * @property string|null $image_normal_path
 * @property string|null $image_medium_path
 * @property string|null $image_thumb_path
 * @property int|null $image_normal_width
 * @property int|null $image_normal_height
 * @property int|null $image_thumb_width
 * @property int|null $image_thumb_height
 * @property int|null $image_medium_width
 * @property int|null $image_medium_height
 * @property int|null $image_original_width
 * @property int|null $image_original_height
 * @property-read string $s3_url
 * @property-read string $s3_thumb_url
 * @property-read string $s3_medium_url
 * @property-read User $user
 * @method static Builder|CommunityAttachment newModelQuery()
 * @method static Builder|CommunityAttachment newQuery()
 * @method static Builder|CommunityAttachment query()
 * @method static Builder|CommunityAttachment whereCommunityId($value)
 * @method static Builder|CommunityAttachment whereCreatedAt($value)
 * @method static Builder|CommunityAttachment whereId($value)
 * @method static Builder|CommunityAttachment whereImageMediumHeight($value)
 * @method static Builder|CommunityAttachment whereImageMediumPath($value)
 * @method static Builder|CommunityAttachment whereImageMediumWidth($value)
 * @method static Builder|CommunityAttachment whereImageNormalHeight($value)
 * @method static Builder|CommunityAttachment whereImageNormalPath($value)
 * @method static Builder|CommunityAttachment whereImageNormalWidth($value)
 * @method static Builder|CommunityAttachment whereImageOriginalHeight($value)
 * @method static Builder|CommunityAttachment whereImageOriginalWidth($value)
 * @method static Builder|CommunityAttachment whereImageThumbHeight($value)
 * @method static Builder|CommunityAttachment whereImageThumbPath($value)
 * @method static Builder|CommunityAttachment whereImageThumbWidth($value)
 * @method static Builder|CommunityAttachment whereMimeType($value)
 * @method static Builder|CommunityAttachment whereOriginalName($value)
 * @method static Builder|CommunityAttachment wherePath($value)
 * @method static Builder|CommunityAttachment whereSize($value)
 * @method static Builder|CommunityAttachment whereUpdatedAt($value)
 * @method static Builder|CommunityAttachment whereUserId($value)
 * @mixin Eloquent
 */
class CommunityAttachment extends Model
{
    use LadaCacheTrait;

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    protected $fillable = [
        'original_name',
        'path',
        'url',
        'user_id',
        'community_id',
        'size',
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

    private function getS3Url($path)
    {
        return Storage::disk('s3')->url($path);
    }
    /**
     * @return string
     */
    public function getS3UrlAttribute()
    {
        return $this->getS3Url($this->path);
    }

    /**
     * @return string
     */
    public function getS3ThumbUrlAttribute()
    {
        return $this->getS3Url($this->image_thumb_path);
    }

    /**
     * @return string
     */
    public function getS3MediumUrlAttribute()
    {
        return $this->getS3Url($this->image_medium_path);
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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
        static::creating(static function ($file) {
            $file->user_id = auth()->user()->id;
        });
    }
}
