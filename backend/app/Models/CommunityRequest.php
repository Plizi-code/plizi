<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommunityRequest extends Model
{
    public const STATUS_NEW = 0;
    public const STATUS_ACCEPTED = 1;
    public const STATUS_REJECTED = -1;

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    protected $fillable = [
        'user_id',
        'community_id',
        'description',
        'status',
    ];

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
        static::creating(static function ($request) {
            $request->user_id = auth()->user()->id;
            $request->status = static::STATUS_NEW;
        });
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_NEW => 'New',
            self::STATUS_ACCEPTED => 'Accepted',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }
}
