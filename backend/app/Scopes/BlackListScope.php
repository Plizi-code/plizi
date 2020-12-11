<?php


namespace App\Scopes;


use App\Models\CommunityMember;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BlackListScope implements Scope
{
    private static $ids = [];

    public function apply(Builder $builder, Model $model)
    {
        if (auth()->guest()) {
            return;
        }
        if ($model instanceof User) {
            $builder
                ->whereNotIn('users.id', $this->getIds());
        }
        if ($model instanceof Post) {
            $builder
                ->whereNotIn('posts.postable_id', $this->getIds())
                ->whereNotIn('posts.author_id', $this->getIds());
        }
        if ($model instanceof CommunityMember) {
            $builder
                ->whereNotIn('community_members.user_id', $this->getIds());
        }
    }

    private function getIds()
    {
        if (!static::$ids) {
            static::$ids = User\Blacklisted::queryForInList()->pluck('user_id');
        }
        return static::$ids;
    }
}
