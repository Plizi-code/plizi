<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    protected $fillable = [
        'user_id', 'viewable_type', 'viewable_id'
    ];

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
        static::creating(function($view) {
            $view->created_at = time();
            $view->updated_at = time();
        });
        static::created(function($view) {
            $view->viewable->increment('views');
        });
        static::deleting(function($view) {
            $view->viewable->decrement('views');
        });
    }

    public function viewable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
