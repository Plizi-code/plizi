<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spiritix\LadaCache\Database\LadaCacheTrait;
use Storage;

/**
 * App\Models\CommunityHeader
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
 * @property-read Community|null $community
 * @property-read string $s3_url
 * @property-read User $user
 * @method static Builder|CommunityHeader newModelQuery()
 * @method static Builder|CommunityHeader newQuery()
 * @method static Builder|CommunityHeader query()
 * @method static Builder|CommunityHeader whereCommunityId($value)
 * @method static Builder|CommunityHeader whereCreatedAt($value)
 * @method static Builder|CommunityHeader whereId($value)
 * @method static Builder|CommunityHeader whereImageMediumHeight($value)
 * @method static Builder|CommunityHeader whereImageMediumPath($value)
 * @method static Builder|CommunityHeader whereImageMediumWidth($value)
 * @method static Builder|CommunityHeader whereImageNormalHeight($value)
 * @method static Builder|CommunityHeader whereImageNormalPath($value)
 * @method static Builder|CommunityHeader whereImageNormalWidth($value)
 * @method static Builder|CommunityHeader whereImageOriginalHeight($value)
 * @method static Builder|CommunityHeader whereImageOriginalWidth($value)
 * @method static Builder|CommunityHeader whereImageThumbHeight($value)
 * @method static Builder|CommunityHeader whereImageThumbPath($value)
 * @method static Builder|CommunityHeader whereImageThumbWidth($value)
 * @method static Builder|CommunityHeader whereMimeType($value)
 * @method static Builder|CommunityHeader whereOriginalName($value)
 * @method static Builder|CommunityHeader wherePath($value)
 * @method static Builder|CommunityHeader whereSize($value)
 * @method static Builder|CommunityHeader whereUpdatedAt($value)
 * @method static Builder|CommunityHeader whereUserId($value)
 * @mixin Eloquent
 */
class CommunityHeader extends Model
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

    /**
     * @return string
     */
    public function getS3UrlAttribute()
    {
        return Storage::disk('s3')->url($this->path);
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo
     */
    public function community()
    {
        return $this->belongsTo(Community::class, 'community_id');
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
