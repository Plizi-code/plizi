<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Domain\Neo4j\Models\User as Neo4jUser;
use Domain\Neo4j\Repositories\UserNeo4jRepository;
use Hootlex\Friendships\Models\Friendship;
use Illuminate\Console\Command;

class FriendshipsSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'friendship:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set or unset user as admin by ID or email';

    /**
     * Execute the console command.
     *
     * @throws \Exception
     */
    public function handle()
    {
        $mysql_users = User::with('profile')->get();
        foreach ($mysql_users as $user) {
            $user = $user->toArray();
            $user['oid'] = $user['id'];
            $user['name'] = $user['profile']['first_name'];
            $user = array_diff_key($user, array_flip(['profile', 'id']));
            $user['created_at'] = new Carbon($user['created_at']);
            $user['updated_at'] = new Carbon($user['updated_at']);
            if(!$neo_user = Neo4jUser::where('oid', $user['oid'])->first()) {
                $this->info("Creating user with email {$user['email']}");
                /** @var User $user */
                $user_created = Neo4jUser::insert($user);
                if($user_created) {
                    $this->info("User with email {$user['email']} created");
                }
            }
        }
        $this->friendship();
    }

    private function friendship()
    {
        (new UserNeo4jRepository())->clearAllRelations();

        $friendships = Friendship::where([
            'status' => 1
        ])->get();
        foreach ($friendships as $friendship) {
            /** @var Neo4jUser $sender */
            $sender = Neo4jUser::where('oid', $friendship->sender_id)->first();
            /** @var Neo4jUser $receiver */
            $receiver = Neo4jUser::where('oid', $friendship->recipient_id)->first();
            $sender->friends()->attach($receiver);
            $this->info("Friendship between {$friendship->sender_id} and {$friendship->recipient_id} created");
        }
    }
}
