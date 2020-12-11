<?php

use App\Models\CommunityTheme;
use Illuminate\Database\Seeder;

class CommunityThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $themes = [
            'Авто, мото' => [
                'Автомобили',
                'Велосипеды',
                'Мототехника',
            ],
            'Города, страны' => [
                'Городское сообщество',
                'Место отдыха',
                'Страна',
            ],
            'Дом, ремонт' => [
                'Дизайн интерьера',
                'Садоводство',
                'Строительство, ремот',
            ],
            'Компьютеры, интернет' => [
                'Видеоигры',
                'Программирование',
                'Сайты',
                'Техника, электроника',
            ],
            'Музыка' => [
                'R&B',
                'Блюз',
                'Джаз',
                'Метал',
                'Рок',
                'Электронная музыка',
            ],
        ];

        foreach ($themes as $parent => $items) {
            $parent = $this->createTheme($parent);
            foreach ($items as $theme) {
                $this->createTheme($theme, $parent->id);
            }
        }
    }

    private function createTheme($name, $parent_id = 0)
    {
        return CommunityTheme::create([
            'parent_id' => $parent_id,
            'name' => $name,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }
}
