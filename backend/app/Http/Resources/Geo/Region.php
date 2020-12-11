<?php

namespace App\Http\Resources\Geo;

use Illuminate\Http\Resources\Json\JsonResource;

class Region extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => [
                'ru' => $this->title_ru,
                'ua' => $this->title_ua,
                'en' => $this->title_en,
            ],
        ];
    }
}
