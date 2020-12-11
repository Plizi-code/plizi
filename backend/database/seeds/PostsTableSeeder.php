<?php

use App\Models\Community;
use Illuminate\Database\Seeder;

use App\Models\Profile;
use App\Models\User;
use App\Models\Post;


class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $communities = Community::without('role')->get();
        foreach ($users as $user) {
            //$post = Post::create($this->generatePost());
            $user->posts()->create($this->generatePost($user->id));
        }
        foreach ($communities as $community) {
            //$post = Post::create($this->generatePost());
            $community->posts()->create($this->generatePost($community->id));
        }
    }

    private function generatePost($id) {
        $faker = Faker\Factory::create('ru_RU');
        return [
            'name' => $faker->realText(15),
            'body' => $faker->realText(300),
            'primary_image' => '',
            'likes' => $faker->numberBetween(0, 1000),
            'views' => $faker->numberBetween(0, 1000),
            'author_id' => $id,
        ];
    }
}
