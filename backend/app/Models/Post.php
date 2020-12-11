<?php

namespace App\Models;

use App\Scopes\BlackListScope;
use App\Traits\Likeable;
use App\Traits\Commentable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spiritix\LadaCache\Database\LadaCacheTrait;

class Post extends Model
{
    use SoftDeletes, LadaCacheTrait, Likeable, Commentable;

    protected $fillable = [
        'name', 'body', 'likes', 'views', 'author_id'
    ];

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function postable() {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function parent() {
        return $this->hasOne( Post::class, 'id', 'parent_id' );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function author() {
        return $this->hasOne( User::class, 'id', 'author_id' );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments() {
        return $this->hasMany(PostAttachment::class, 'post_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes() {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function like() {
        return $this->morphMany(Like::class, 'likeable')
            ->where('user_id', \Auth::user()->id);
    }

    public function alreadyViewed()
    {
        return $this->morphMany(View::class, 'viewable')
            ->where('user_id', \Auth::user()->id);
    }

    public function usersLikes()
    {
        return $this->hasManyThrough(
            User::class,
            Like::class,
            'likeable_id',
            'id',
            'id',
            'user_id'
        )->where('likeable_type', Post::class);
    }

    public function shortUsersLikes()
    {
        return $this->hasManyThrough(
            User::class,
            Like::class,
            'likeable_id',
            'id',
            'id',
            'user_id'
        )
            ->where('likeable_type', Post::class)
            ->take(8);
    }

    public function video()
    {
        return $this->morphOne(Video::class, 'creatableby');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    /**
     * @return string
     */
    public function getDateFormat()
    {
        return 'U';
    }

    /**
     * @param User $user
     * @param int $limit
     * @param int $offset
     * @param bool $isMyPosts
     * @param bool $onlyLiked
     * @param null $orderBy
     * @param string $search
     * @param array $parts
     * @return array|Builder[]|Collection
     */
    public static function getWithoutOldPosts($user, $limit, $offset, $isMyPosts = false, $onlyLiked = false, $orderBy = null, $search = '', $parts = [])
    {
        if ($user->id !== \Auth::id()) {
            $isFriend = $user->isFriendWith(auth()->user());
            if (($user->privacySettings->page_type === 2 && !$isFriend) ||
                $user->privacySettings->page_type === 3) {
                return [];
            }
        }

        if ($isMyPosts) {
            $userPosts = $user->posts()->pluck('id');

            return self::whereIn('id', $userPosts)->with([
                    'like',
                    'alreadyViewed',
                    'postable',
                    'author',
                    'shortUsersLikes',
                    'parent' => static function ($query) {
                    return $query->withTrashed()->get();
                }, 'attachments' => static function ($query) {
                    return $query->withCount('comments');
            }])->withCount('comments', 'children')
                ->search($search)
                ->limit($limit ?? 20)
                ->offset($offset ?? 0)
                ->orderBy('id', 'desc')
                ->get();
        }

        return self::with([
            'like',
            'alreadyViewed',
            'postable',
            'author',
            'shortUsersLikes',
            'parent' => static function ($query) {
                return $query->withTrashed()->get();
            },
            'attachments' => static function ($query) {
                return $query->withCount('comments');
            }
        ])
            ->withCount('comments', 'children')
            ->where(static function ($query) use ($onlyLiked) {
                if ($onlyLiked) {
                    $query->where('likes', '>', 0);
                }
            })
            ->own($parts)
            ->communities($user, $parts)
            ->friends($user, $parts)
            ->search($search)
            ->limit($limit ?? 20)
            ->offset($offset ?? 0)
            ->orderBy($orderBy ?? 'id', 'desc')
            ->get();
    }

    public function scopeOwn(Builder $query, $parts)
    {
        if ($parts && !in_array('own', $parts, true)) {
            return;
        }
        $query->orWhere(static function ($query) {
            $query->where('postable_type', User::class)
                ->where('postable_id', \Auth::user()->id);
        });
    }

    /**
     * @param Builder $query
     * @param User $user
     * @param $parts
     */
    public function scopeFriends(Builder $query, $user, $parts)
    {
        if ($parts && !in_array('friends', $parts, true)) {
            return;
        }
        DB::table('friendships')
            ->where('status', 1)
            ->where(static function($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere('recipient_id', $user->id);
            })
            ->select('id', 'sender_id', 'recipient_id', 'created_at')
            ->get()
            ->each(static function ($friend, $key) use ($query, $user) {
                if ($friend->sender_id !== $user->id) {
                    $query->orWhere(static function ($query) use ($friend) {
                        $query->where('postable_type', User::class)
                            ->where('postable_id', $friend->sender_id)
                            ->where('created_at', '>', Carbon::parse($friend->created_at)->timestamp);
                    });
                }

                if ($friend->recipient_id !== $user->id) {
                    $query->orWhere(static function ($query) use ($friend) {
                        $query->where('postable_type', User::class)
                            ->where('postable_id', $friend->recipient_id)
                            ->where('created_at', '>', Carbon::parse($friend->created_at)->timestamp);
                    });
                }
            });
    }

    public function scopeCommunities(Builder $query, $user, $parts)
    {
        if ($parts && !in_array('communities', $parts, true)) {
            return;
        }
        $user->communities()
            ->select('id')
            ->get()
            ->each(static function ($community, $key) use ($query) {
                $query->orWhere(static function($query) use ($community) {
                    $query->where('postable_type', Community::class)
                        ->where('postable_id', $community->id)
                        ->where('created_at', '>', Carbon::parse($community->pivot->created_at)->timestamp);
                });
            });
    }

    /**
     * @param Builder $query
     * @param string $search
     */
    public function scopeSearch(Builder $query, $search)
    {
        if ($search && mb_strlen($search) >= 3) {
            $query
                ->where(static function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('body', 'LIKE', "%{$search}%");
                });
        }
    }

    /**
     * @param null|User $user
     * @return bool
     */
    public function userHasAccess($user = null)
    {
        $user = $user ?: auth()->user();
        if ($this->postable instanceof Community) {
            $role = $this->postable->role;
            return $role && in_array($role->role, [Community::ROLE_ADMIN, Community::ROLE_AUTHOR], true);
        }

        return $this->author_id === $user->id;
    }

    public static function boot()
    {
        parent::boot();
        self::addGlobalScope(new BlackListScope());
    }
}
