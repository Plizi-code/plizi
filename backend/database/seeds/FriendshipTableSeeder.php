<?php

use Illuminate\Database\Seeder;

use App\Models\Profile;
use App\Models\User;


class FriendshipTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = User::where('email', 'test@gmail.com')->first();
        $user2 = User::where('email', 'admin@mail.com')->first();
        $users = User::all();
        for ($i = 0; $i <= count($users) / 2; $i++) {
            if(($user1->id && isset($users[$i])) && $user1->id !== $users[$i]->id) {
                DB::table('friendships')->insert([
                    'sender_id' => $user1->id,
                    'sender_type' => 'App\Models\User',
                    'recipient_id' => $users[$i]->id,
                    'recipient_type' => 'App\Models\User',
                    'status' => 1,
                    'created_at' => \Illuminate\Support\Carbon::now(),
                    'updated_at' => \Illuminate\Support\Carbon::now(),
                ]);
            }
        }
        for ($i = count($users) / 2; $i <= count($users); $i++) {
            if(($user2->id && $users[$i]) && $user2->id !== $users[$i]->id) {
                DB::table('friendships')->insert([
                    'sender_id' => $user2->id,
                    'sender_type' => 'App\Models\User',
                    'recipient_id' => $users[$i]->id,
                    'recipient_type' => 'App\Models\User',
                    'status' => 1,
                    'created_at' => \Illuminate\Support\Carbon::now(),
                    'updated_at' => \Illuminate\Support\Carbon::now(),
                ]);
            }
        }
        $users = $users->pluck('id')->toArray();
        while (count($users) > 1) {
            $user1 = $users[array_rand($users)];
            $users = array_filter($users, function($e) use ($user1) {
                return ($e !== $user1);
            });;
            $user2 = $users[array_rand($users)];
            if($user1 && $user2) {
                DB::table('friendships')->insert([
                    'sender_id' => $user1,
                    'sender_type' => 'App\Models\User',
                    'recipient_id' => $user2,
                    'recipient_type' => 'App\Models\User',
                    'status' => 1,
                    'created_at' => \Illuminate\Support\Carbon::now(),
                    'updated_at' => \Illuminate\Support\Carbon::now(),
                ]);
            }
        }
    }
}
