<?php

use Illuminate\Database\Seeder;
use App\Models\Geo\City;

class CitiesGeoSeeder extends GeoSeeder
{
    function getGeoDataFile(): string
    {
        return "/geo_data/_cities_new.csv";
    }

    function getDataConfig(): array
    {
        return [
            'city_id' => 'id',
            'country_id' => 'country_id',
            'important' => 'important',
            'region_id' => 'region_id',
            'title_ru' => 'title_ru',
            'area_ru' => 'area_ru',
            'region_ru' => 'region_ru',
            'title_ua' => 'title_ua',
            'area_ua' => 'area_ua',
            'region_ua' => 'region_ua',
            'title_en' => 'title_en',
            'area_en' => 'area_en',
            'region_en' => 'region_en',
        ];
    }

    function createGeoData(array $data): void
    {
        City::insert($data);
    }

    protected function prepareData($data)
    {
        $data['region_id'] = (int) $data['region_id'];
        $data['country_id'] = (int) $data['country_id'];
        return $data;
    }

    protected function checkData($data)
    {
        return in_array((int) $data['country_id'], [1, 2, 3, 4]);
    }
}
