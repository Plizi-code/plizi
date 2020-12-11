<?php

namespace Domain\Pusher\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Profile extends Model
{
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $connection = 'mongodb';

    const SEX_MALE = 'm';
    const SEX_FEMALE = 'f';
    const SEX_UNDEFINED = 'n';

    const SEX_VARIANTS = [
        self::SEX_FEMALE => 'female',
        self::SEX_MALE => 'male',
        self::SEX_UNDEFINED => 'undefined'
    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'birthday',
        'city',
        'sex',
        'relationship_id',
        'relationship_user_id',
        'user_pic',
        'geo_city_id',
    ];
}
