<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoAlbum extends Model
{
    protected $fillable = [
        'author_id',
        'title',
        'description',
        'creatable_id',
        'creatable_type',
    ];

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    /**
     * @return string
     */
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

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function images()
    {
        return $this->belongsToMany(
            ImageUpload::class,
            'image_upload_photo_album',
            'photo_album_id',
            'image_upload_id'
        );
    }
}
