<?php


namespace App\Traits;


use App\Models\Comment;

trait Commentable
{

    /**
     * @return mixed
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('reply_on')->with('children', 'author', 'author.profile', 'author.profile.avatar');
    }
}
