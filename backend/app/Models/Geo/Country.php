<?php

namespace App\Models\Geo;

use Illuminate\Database\Eloquent\Model;
use Spiritix\LadaCache\Database\LadaCacheTrait;

class Country extends Model
{

    use LadaCacheTrait;
    protected $table = 'geo_countries';

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
}
