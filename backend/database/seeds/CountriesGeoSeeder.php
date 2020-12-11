<?php

use Illuminate\Database\Seeder;
use App\Models\Geo\Country;

class CountriesGeoSeeder extends GeoSeeder
{
    function getGeoDataFile(): string
    {
        return "/geo_data/_countries.csv";
    }

    function getDataConfig(): array
    {
        return [
            'country_id' => 'id',
            'title_ru' => 'title_ru',
            'title_ua' => 'title_ua',
            'title_en' => 'title_en',
        ];
    }

    function createGeoData(array $data): void
    {
        Country::insert($data);
    }
}
