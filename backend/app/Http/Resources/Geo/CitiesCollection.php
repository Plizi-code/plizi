<?php

namespace App\Http\Resources\Geo;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CitiesCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'list' => $this->collection->map(function ($city) {
                return [
                    'id' => $city->id,
                    'title' => [
                        'ru' => $city->title_ru,
                        'ua' => $city->title_ua,
                        'en' => $city->title_en,
                    ],
                    'region' => $city->region ? new Region($city->region) : null,
                    'country' => new Country($city->country),
                ];
            }),
        ];
    }
}
