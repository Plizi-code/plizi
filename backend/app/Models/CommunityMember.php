<?php

namespace App\Models;

use App\Scopes\BlackListScope;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * App\CommunityMember
 *
 * @property int $community_id
 * @property string $user_id
 * @property string $role
 * @property int $created_at
 * @property int $updated_at
 * @method static Builder|CommunityMember newModelQuery()
 * @method static Builder|CommunityMember newQuery()
 * @method static Builder|CommunityMember query()
 * @method static Builder|CommunityMember whereCommunityId($value)
 * @method static Builder|CommunityMember whereCreatedAt($value)
 * @method static Builder|CommunityMember whereRole($value)
 * @method static Builder|CommunityMember whereUpdatedAt($value)
 * @method static Builder|CommunityMember whereUserId($value)
 * @mixin Eloquent
 */
class CommunityMember extends Pivot
{
    use LadaCacheTrait;

    protected $table = 'community_members';

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    public function getDateFormat()
    {
        return 'U';
    }

    public static function boot()
    {
        parent::boot();
        self::addGlobalScope(new BlackListScope());
    }
}
