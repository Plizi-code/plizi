<?php


namespace app\components\response;


use app\models\GeoCities;

class City
{
    public function toArray(GeoCities $city)
    {
        return [
            'id' => $city->id,
            'title' => [
                'ru' => $city->title_ru,
                'ua' => $city->title_ua,
                'en' => $city->title_en,
            ],
//            'region' => new Region($this->region),
//            'country' => new Country($this->country),
        ];
    }

    public function __invoke($city)
    {
        return $this->toArray($city);
    }
}
