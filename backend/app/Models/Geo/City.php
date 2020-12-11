<?php

namespace App\Models\Geo;

use Illuminate\Database\Eloquent\Model;
use Spiritix\LadaCache\Database\LadaCacheTrait;

class City extends Model
{

    use LadaCacheTrait;
    protected $table = 'geo_cities';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    public function getDateFormat()
    {
        return 'U';
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function region()
    {
        return $this->hasOne(Region::class, 'id', 'region_id');
    }
}
