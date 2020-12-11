<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'link',
        'user_id',
        'creatableby_id',
        'creatableby_type',
    ];

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    public function getDateFormat()
    {
        return 'U';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creatableby()
    {
        return $this->morphTo();
    }

    /**
     * @param Builder $query
     * @param string $creatablebyClass
     * @param string $postableTypeClass
     * @param int $postableId
     */
    public function scopeSpecial(Builder $query, $creatablebyClass, $postableTypeClass, $postableId)
    {
        $query->whereHasMorph('creatableby', $creatablebyClass, static function (Builder $creatableby) use ($postableTypeClass, $postableId) {
            $creatableby
                ->where([
                    'postable_type' => $postableTypeClass,
                    'postable_id' => $postableId,
                ]);
        });
    }

    /**
     * @param Builder $query
     * @param int $communityId
     */
    public function scopeSpecialForCommunity(Builder $query, $communityId)
    {
        return $this->scopeSpecial($query, Post::class, Community::class, $communityId);
    }
}
