<?php


namespace App\Models;


use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    protected $casts = [
        'data' => 'array',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'read_at' => 'timestamp',
    ];

    public function getDateFormat()
    {
        return 'U';
    }
}
