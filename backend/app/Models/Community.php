<?php

namespace App\Models;

use App\Models\Geo\City;
use App\Traits\Neo4jFavorite;
use Auth;
use Domain\Neo4j\Service\UserService;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * App\Models\Community
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $notice
 * @property string|null $primary_image
 * @property string|null $url
 * @property string|null $website
 * @property int $is_verified
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $type
 * @property int $theme_id
 * @property int $privacy
 * @property int|null $geo_city_id
 * @property int $video_count
 * @property-read Collection|User[] $admins
 * @property-read int|null $admins_count
 * @property-read Collection|User[] $authors
 * @property-read int|null $authors_count
 * @property-read CommunityAttachment|null $avatar
 * @property-read City|null $city
 * @property-read CommunityHeader|null $headerImage
 * @property-read Collection|CommunityMember[] $members
 * @property-read int|null $members_count
 * @property-read Collection|Post[] $posts
 * @property-read int|null $posts_count
 * @property-read Collection|CommunityRequest[] $requests
 * @property-read int|null $requests_count
 * @property-read CommunityMember|null $role
 * @property-read CommunityTheme $theme
 * @property-read Collection|User[] $users
 * @property-read int|null $users_count
 * @property-read Collection|User[] $subscribers
 * @property-read int|null $subscribers_count
 * @property-read Collection|User[] $supers
 * @property-read int|null $supers_count
 * @method static Builder|Community newModelQuery()
 * @method static Builder|Community newQuery()
 * @method static Builder|Community onlyMy()
 * @method static Builder|Community owner()
 * @method static Builder|Community showedForAll()
 * @method static Builder|Community query()
 * @method static Builder|Community search($search)
 * @method static Builder|Community whereCreatedAt($value)
 * @method static Builder|Community whereDescription($value)
 * @method static Builder|Community whereGeoCityId($value)
 * @method static Builder|Community whereId($value)
 * @method static Builder|Community whereIsVerified($value)
 * @method static Builder|Community whereName($value)
 * @method static Builder|Community whereNotice($value)
 * @method static Builder|Community wherePrimaryImage($value)
 * @method static Builder|Community wherePrivacy($value)
 * @method static Builder|Community whereThemeId($value)
 * @method static Builder|Community whereType($value)
 * @method static Builder|Community whereUpdatedAt($value)
 * @method static Builder|Community whereUrl($value)
 * @method static Builder|Community whereWebsite($value)
 * @mixin Eloquent
 */
class Community extends Model
{
    use LadaCacheTrait, Neo4jFavorite;

    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';
    const ROLE_AUTHOR = 'author';
    const ROLE_GUEST = 'guest';

    public const PRIVACY_OPEN = 1;
    public const PRIVACY_CLOSED = 2;
    public const PRIVACY_PRIVATE = 3;

    public const TYPE_BUSINESS = 1;
    public const TYPE_THEMES = 2;
    public const TYPE_BRAND = 3;
    public const TYPE_INTEREST_GROUP = 4;
    public const TYPE_PUBLIC_PAGE = 5;
    public const TYPE_EVENT = 6;

    protected $fillable = [
        'name', 'description', 'notice', 'primary_image', 'url', 'website', 'geo_city_id', 'is_verified', 'type', 'theme_id', 'privacy',
    ];

    protected $with = ['role'];

    /**
     * @return BelongsToMany
     */
    public function users()
    {
        return $this
            ->belongsToMany(User::class, 'community_members')
            ->withPivot(['role'])
            ->wherePivot('role', '!=', self::ROLE_GUEST);
    }

    /**
     * @return BelongsToMany
     */
    public function subscribers()
    {
        return $this
            ->belongsToMany(User::class, 'community_members')
            ->withPivot(['role'])
            ->wherePivot('subscribed', true);
    }

    public function onlyFiveMembers()
    {
        return $this->users()->take(5);
    }

    /**
     * @return HasMany
     */
    public function members()
    {
        return $this->hasMany(CommunityMember::class)->where('role', '!=', self::ROLE_GUEST);
    }

    /**
     * @return HasMany
     */
    public function requests()
    {
        return $this->hasMany(CommunityRequest::class);
    }

    /**
     * @return BelongsToMany
     */
    public function supers()
    {
        return $this->belongsToMany(User::class, 'community_members')
            ->withPivot(['role'])
            ->wherePivotIn('role', [self::ROLE_AUTHOR, self::ROLE_ADMIN]);
    }

    /**
     * @return BelongsToMany
     */
    public function authors()
    {
        return $this->belongsToMany(User::class, 'community_members')->withPivot(['role'])->wherePivot('role', self::ROLE_AUTHOR);
    }

    /**
     * @return BelongsToMany
     */
    public function admins()
    {
        return $this->belongsToMany(User::class, 'community_members')->withPivot(['role'])->wherePivot('role', self::ROLE_ADMIN);
    }

    /**
     * @return BelongsToMany
     */
    public function managers()
    {
        return $this->belongsToMany(User::class, 'community_members')->withPivot(['role'])->wherePivot('role', self::ROLE_ADMIN)->orWherePivot('role', self::ROLE_AUTHOR);
    }

    /**
     * @return HasOne
     */
    public function role()
    {
        return $this->hasOne(CommunityMember::class)->where('community_members.user_id', Auth::user() ? Auth::user()->id : 0);
    }

    /**
     * @return BelongsTo
     */
    public function theme()
    {
        return $this->belongsTo(CommunityTheme::class);
    }

    /**
     * @return MorphMany
     */
    public function posts()
    {
        return $this->morphMany(Post::class, 'postable');
    }

    /**
     * @return HasOne
     */
    public function avatar() {
        return $this->hasOne(CommunityAttachment::class);
    }

    /**
     * @return HasOne
     */
    public function headerImage() {
        return $this->hasOne(CommunityHeader::class);
    }

    /**
     * @return HasOne
     */
    public function city()
    {
        return $this->hasOne(City::class, 'id', 'geo_city_id');
    }

    /**
     * @return string
     */
    public function getDateFormat()
    {
        return 'U';
    }

    public static function getPrivacyList()
    {
        return [
            self::PRIVACY_OPEN => 'Открытое',
            self::PRIVACY_CLOSED => 'Закрытое',
            self::PRIVACY_PRIVATE => 'Приватное',
        ];
    }

    public static function getTypeList()
    {
        return [
            self::TYPE_BUSINESS => 'Бизнес',
            self::TYPE_THEMES => 'Тематическое сообщество',
            self::TYPE_BRAND => 'Бренд или организация',
            self::TYPE_INTEREST_GROUP => 'Группа по интересам',
            self::TYPE_PUBLIC_PAGE => 'Публичная страница',
            self::TYPE_EVENT => 'Мероприятие',
        ];
    }

    public function friends($limit = 5, $offset = 0)
    {
        if (Auth::guest()) {
            return [];
        }
        return (new UserService())->getFriendsFromCommunity(Auth::user()->id, $this->id, $limit, $offset);
    }

    public function getNeo4jNodeName()
    {
        return 'Community';
    }

    public function getNeo4jRelationName()
    {
        return 'COMMUNITY_FAVORITE';
    }

    /**
     * @param Builder $query
     */
    public function scopeOnlyMy(Builder $query)
    {
        $query->whereHas('role', static function (Builder $query) {
            $query
                ->where([
                    'user_id' => Auth::guest() ? '0' : Auth::user()->id,
                ])
                ->where('role', '!=', self::ROLE_GUEST);
        });
    }

    /**
     * @param Builder $query
     */
    public function scopeOwner(Builder $query)
    {
        $query->whereHas('role', static function (Builder $query) {
            $query
                ->where([
                    'user_id' => Auth::guest() ? '0' : Auth::user()->id,
                ])
                ->where(static function (Builder $query) {
                    $query
                        ->where([
                            'role' => Community::ROLE_ADMIN,
                        ])
                        ->orWhere([
                            'role' => Community::ROLE_AUTHOR,
                        ]);
                });
        });
    }

    /**
     * @param Builder $query
     * @param string $search
     */
    public function scopeSearch(Builder $query, $search)
    {
        $query
            ->where('name', 'LIKE', "%{$search}%")
            ->orWhere('notice', 'LIKE', "%{$search}%")
            ->orWhere('description', 'LIKE', "%{$search}%")
            ->orWhere('url', 'LIKE', "%{$search}%")
            ->orWhere('website', 'LIKE', "%{$search}%");
    }

    /**
     * @param Builder $query
     */
    public function scopeShowedForAll(Builder $query)
    {
        $query
            ->where(static function(Builder $q) {
                $q
                    ->whereNotIn('privacy', [self::PRIVACY_PRIVATE])
                    ->orWhereHas('role', static function (Builder $query) {
                        $query
                            ->where([
                                'user_id' => Auth::guest() ? '0' : Auth::user()->id,
                            ])
                            ->where('role', '!=', self::ROLE_GUEST);
                    });
            });
    }

    public function isUserHasAccess($user = null)
    {
        $user = $user ?: auth()->user();
        if ($this->privacy === self::PRIVACY_OPEN) {
            return true;
        }

        return $this->isMember($user);
    }

    /**
     * @return MorphMany
     */
    public function photoAlbums()
    {
        return $this->morphMany(PhotoAlbum::class, 'creatable');
    }
}
