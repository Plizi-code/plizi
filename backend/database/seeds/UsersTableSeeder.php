<?php

use Illuminate\Database\Seeder;

use App\Models\Profile;
use App\Models\User;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $email1 = 'test@gmail.com';
        $email2 = 'admin@mail.com';
        $email3 = 'user@mail.com';
        $countOfUsers = App::environment() != 'testing' ? 10 : 1;

        if (App::environment() != 'testing') {
            $email1 = $this->command->ask('Enter email of first user', 'test@gmail.com');
            $email2 = $this->command->ask('Enter email of admin user', 'admin@mail.com');
            $countOfUsers = $this->command->ask('How many users you want to generate', 10);
        }

        $user1 = User::where('email', $email1)->first();
        if (!$user1) {
            /** @var User $user1 */
            $user1 = User::create([
                'email' => $email1,
                'password' => bcrypt('secret'),
                'token' => bcrypt('secret'),
                'last_activity_dt' => time(),
                'created_at' => time(),
                'updated_at' => time(),
            ]);
            $user1->profile()->create($this->generateProfile());
            $user1->refresh();
            $this->command->line("Generate user with email {$email1}");
        } else {
            $this->command->line("User with email {$email1} already exists");
        }

        $user2 = User::where('email', $email2)->first();
        if (!$user2) {
            /** @var User $user2 */
            $user2 = User::create([
                'email' => $email2,
                'password' => bcrypt('secret'),
                'token' => bcrypt('secret'),
                'last_activity_dt' => time(),
                'is_admin' => true,
                'created_at' => time(),
                'updated_at' => time(),
            ]);
            $user2->profile()->create($this->generateProfile());
            $this->command->line("Generate user with email {$email2}");
        } else {
            $this->command->line("User with email {$email2} already exists");
        }

        $user3 = User::where('email', $email3)->first();
        if (!$user3) {
            /** @var User $user3 */
            $user3 = User::create([
                'email' => $email3,
                'password' => bcrypt('secret'),
                'token' => bcrypt('secret'),
                'last_activity_dt' => time(),
                'is_admin' => true,
                'created_at' => time(),
                'updated_at' => time(),
            ]);
            $user3->profile()->create($this->generateProfile());
            $this->command->line("Generate user with email {$email3}");
        } else {
            $this->command->line("User with email {$email3} already exists");
        }

        $faker = Faker\Factory::create();
        for ($i = 0; $i <= $countOfUsers; $i++) {
            $fakeEmail = $faker->email;
            $user = User::where('email', $fakeEmail)->first();
            if (!$user) {
                /** @var User $user */
                $user = User::create([
                    'email' => $faker->email,
                    'password' => bcrypt('secret'),
                    'token' => bcrypt('secret'),
                    'last_activity_dt' => time(),
                    'created_at' => time(),
                    'updated_at' => time(),
                ]);
                $user->profile()->create($this->generateProfile());
                $this->command->line("Generate user with email {$user->email}");
            } else {
                $i--;
            }
        }
    }

    private function generateProfile() {
        $faker = Faker\Factory::create('ru_RU');
        $rand = $faker->numberBetween(0, 1000);
        return [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'birthday' => $faker->dateTimeBetween('-70 years', '-20 years')->format('Y-m-d'),
            'geo_city_id' => null,
            'sex' => $faker->randomElement(['n', 'm', 'f']),
            'user_pic' => "https://i.picsum.photos/id/$rand/500/500.jpg",
            'created_at' => time(),
            'updated_at' => time(),
        ];
    }
}
