<?php

namespace App\Traits;

use App\Models\Like;

trait Likeable
{
    public function userLikes()
    {
        return $this->morphMany(Like::class, 'likeable')->with(['user', 'user.profile', 'user.profile.avatar']);
    }
}
