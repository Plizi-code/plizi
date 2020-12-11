<?php

namespace App\Http\Resources\Geo;

use Illuminate\Http\Resources\Json\JsonResource;

class City extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => [
                'ru' => $this->title_ru,
                'ua' => $this->title_ua,
                'en' => $this->title_en,
            ],
            'region' => new Region($this->region),
            'country' => new Country($this->country),
        ];
    }
}
