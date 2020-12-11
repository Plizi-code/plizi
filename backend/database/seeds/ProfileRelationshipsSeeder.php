<?php

use Illuminate\Database\Seeder;
use App\Models\Profile\Relationship;

class ProfileRelationshipsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $titles = [
            'В браке',
            'Не в браке',
            'В активном поиске',
            'Встречаюсь',
            'В отношениях',
        ];

        foreach ($titles as $title) {
            $relationship = Relationship::where('title', $title)->first();

            if(!$relationship) {

                $relationship = Relationship::create([
                    'title' => $title,
                    'created_at' => time(),
                    'updated_at' => time()
                ]);
            }
        }
    }
}
