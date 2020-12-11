<?php

namespace App\Models\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blacklisted extends Model
{
    protected $table = 'users_blacklisted';

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    protected $fillable = [
        'user_id', 'blacklisted_id',
    ];

    /**
     * @return string
     */
    public function getDateFormat()
    {
        return 'U';
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'blacklisted_id');
    }

    /**
     * @return Builder
     */
    public static function queryForInList()
    {
        return self::query()->where('blacklisted_id', auth()->id());
    }
}
