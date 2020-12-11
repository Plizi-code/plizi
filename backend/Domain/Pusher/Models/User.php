<?php

namespace Domain\Pusher\Models;

use DateTime;
use Jenssegers\Mongodb\Eloquent\Model;

class User extends Model
{

    protected $connection = 'mongodb';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'token', 'last_activity_dt', 'created_at', 'updated_at', 'id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function profile() {
        return $this->embedsOne(Profile::class);
    }

    public function messages() {
        return $this->hasMany(ChatMessage::class);
    }
}
