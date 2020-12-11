<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinkedSocialAccount extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'provider_name',
        'provider_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDateFormat()
    {
        return 'U';
    }
}
