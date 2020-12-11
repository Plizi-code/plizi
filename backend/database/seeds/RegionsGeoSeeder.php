<?php

use Illuminate\Database\Seeder;
use App\Models\Geo\Region;

class RegionsGeoSeeder extends GeoSeeder
{
    function getGeoDataFile(): string
    {
        return "/geo_data/_regions.csv";
    }

    function getDataConfig(): array
    {
        return [
            'region_id' => 'id',
            'country_id' => 'country_id',
            'title_ru' => 'title_ru',
            'title_ua' => 'title_ua',
            'title_en' => 'title_en',
        ];
    }

    function createGeoData(array $data): void
    {
        Region::insert($data);
    }
}
