<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * App\Models\CommunityTheme
 *
 * @property int $id
 * @property int $parent_id
 * @property string $name
 * @property int $updated_at
 * @property int $created_at
 * @property-read Collection|CommunityTheme[] $children
 * @property-read int|null $children_count
 * @property-read CommunityTheme $parent
 * @method static Builder|CommunityTheme newModelQuery()
 * @method static Builder|CommunityTheme newQuery()
 * @method static Builder|CommunityTheme query()
 * @method static Builder|CommunityTheme whereCreatedAt($value)
 * @method static Builder|CommunityTheme whereId($value)
 * @method static Builder|CommunityTheme whereName($value)
 * @method static Builder|CommunityTheme whereParentId($value)
 * @method static Builder|CommunityTheme whereUpdatedAt($value)
 * @mixin Eloquent
 */
class CommunityTheme extends Model
{
    use LadaCacheTrait;

    public function getDateFormat()
    {
        return 'U';
    }

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    protected $fillable = [
        'parent_id',
        'name',
    ];

    /**
     * @return BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function children()
    {
        return $this->hasMany(__CLASS__, 'parent_id')->orderBy('name');
    }

    /**
     * @return mixed
     */
    public static function getTree()
    {
        $all = self::all();
        return $all
            ->filter(static function ($theme) {
                return $theme->parent_id === 0;
            })
            ->sortBy('name')
            ->load('children')
            ->values();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public static function getParents()
    {
        $all = self::all();
        return $all
            ->filter(static function ($theme) {
                return $theme->parent_id === 0;
            })
            ->sortBy('name')
            ->pluck('name', 'id')
            ->prepend('Root', 0);
    }

    public static function getAllChildren()
    {
        $all = self::all();
        return $all
            ->reject(static function ($theme) {
                return $theme->parent_id === 0;
            });
    }
}
