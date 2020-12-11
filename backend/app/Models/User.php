<?php

namespace App\Models;

use App\Models\Rbac\Role;
use App\Models\User\Blacklisted;
use App\Models\User\PrivacySettings;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use App\Scopes\BlackListScope;
use App\Traits\Friendable;
use Domain\Neo4j\Service\UserService;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spiritix\LadaCache\Database\LadaCacheTrait;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laratrust\Traits\LaratrustUserTrait;
use MongoDB\BSON\ObjectId;

class User extends Authenticatable implements JWTSubject
{

    use LaratrustUserTrait, Notifiable, Friendable, LadaCacheTrait;

    const PERMISSION_ROLE_FOF = 'friendOfFriend';//friend of friend
    const PERMISSION_ROLE_FRIEND = 'friend';
    const PERMISSION_ROLE_GUEST = 'guest';

    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'token', 'last_activity_dt', 'uuid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // protected $with = ['profile'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function privacySettings()
    {
        $default = (int) Role::where('flag', Role::FLAG_DEFAULT)->get()->first()->id ?? 0;
        return $this->hasOne(PrivacySettings::class)->withDefault(
            [
                'page_type' => $default,
                'write_messages_permissions' => $default,
                'post_wall_permissions' => $default,
                'view_wall_permissions' => $default,
                'view_friends_permissions' => $default,
                'two_factor_auth_enabled' => PrivacySettings::TWO_FACTOR_AUTH_ENABLED_DEFAULT,
                'sms_confirm' => PrivacySettings::SMS_CONFIRM_DEFAULT,
            ]
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function communities()
    {
        return $this->belongsToMany(Community::class, 'community_members')
            ->using(CommunityMember::class)->withPivot('role')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')
            ->orderBy('created_at', 'desc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function posts()
    {
        return $this->morphMany(Post::class, 'postable')->with('parent');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function allPosts()
    {
        return $this->morphMany(Post::class, 'postable')->with('postable', 'parent', 'attachments', 'like')
            ->orWhereIn( 'postable_id', self::communities()->allRelatedIds())
            ->orWhereIn( 'postable_id', array_keys(self::getFriends()))->orderBy('posts.id', 'desc');
    }

    /**
     * @return mixed
     */
    public function getRecommendedFriendsAttribute()
    {
        $community_ids = $this->communities()->pluck('id');
        $user_ids = \DB::table('community_members')->whereIn('community_id', $community_ids)->where('user_id', '<>', $this->id)->pluck('user_id');
        $user_ids = array_count_values(json_decode(json_encode($user_ids)));
        return self::with('profile', 'profile.avatar')->whereIn('id', array_keys($user_ids))->whereNotIn('id', array_column($this->getFriends(), 'id'))->get()->sortByDesc(function ($value) use ($user_ids) {
            return $user_ids[$value['id']];
        })->values();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function linkedSocialAccounts()
    {
        return $this->hasMany(LinkedSocialAccount::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    /**
     * @return int
     */
    public function getNotificationsCountAttribute()
    {
        return $this->unreadNotifications()->count();
    }

    /**
     * @return int
     */
    public function getPendingFriendshipRequestsCountAttribute()
    {
        return $this->getFriendRequests()->count();
    }

    /**
     * @return int
     */
    public function getTotalFriendsCountAttribute()
    {
        $friends = $this->getFriends();
        return isset($friends[array_key_first($friends)]) ? $friends[array_key_first($friends)]['total_count'] : 0;
    }

    /**
     * @return int
     */
    public function getMutualFriendsCountAttribute()
    {
        return \Auth::user()->getMutualFriendsCount($this);
    }

    public function getIsOnlineAttribute()
    {
        $period = config('app.user_activity_margin');
        return $this->last_activity_dt > strtotime("-$period minutes");
    }

    /**
     * @return int
     */
    public function getUnreadMessagesCountAttribute() {
        return 0;
    }

    /**
     * @return bool
     */
    public function getIsFollowAttribute()
    {
        return (new UserService())->isFollowed(auth()->user()->id, $this->id);
    }

    public function getDateFormat()
    {
        return 'U';
    }

    public function isAdmin()
    {
        return (int) $this->is_admin === 1;
    }

    public function isSuperAdmin()
    {
        return (int) $this->is_admin === 1;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * @param User $user
     * @return string
     */
    public function getUserPrivacyRole(User $user)
    {
        if ($this->isFriendOfFriendWith($user->id)) {
            return Role::where('name', self::PERMISSION_ROLE_FOF)->get()->first();
        }
        if ($this->isFriendWith($user)) {
            return Role::where('name', self::PERMISSION_ROLE_FRIEND)->get()->first();
        }
        return Role::where('name', self::PERMISSION_ROLE_GUEST)->get()->first();
    }

    public function blacklistUsers()
    {
        return $this->hasManyThrough(
            __CLASS__,
            Blacklisted::class,
            'user_id',
            'id',
            'id',
            'blacklisted_id'
        );
    }

    public function inBlacklistUsers()
    {
        return $this->hasManyThrough(
            __CLASS__,
            Blacklisted::class,
            'blacklisted_id',
            'id',
            'id',
            'user_id'
        );
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function photoAlbumsByAuthor()
    {
        return $this->hasMany(PhotoAlbum::class, 'author_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function photoAlbums()
    {
        return $this->morphMany(PhotoAlbum::class, 'creatable');
    }

    public function images()
    {
        return $this->hasMany(ImageUpload::class);
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->id = new ObjectId();
        });
        self::addGlobalScope(new BlackListScope());
    }
}
