<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Domain\Pusher\Models\Profile;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Expression;
use MongoDB\BSON\ObjectId;
use Ramsey\Uuid\Uuid;

class UsersSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set or unset user as admin by ID or email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

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
            $profile = $user['profile'];
            $user = array_diff_key($user, array_flip(['profile']));
            $user['created_at'] = new Carbon($user['created_at']);
            $user['updated_at'] = new Carbon($user['updated_at']);
            $profile['created_at'] = new Carbon($profile['created_at']);
            $profile['updated_at'] = new Carbon($profile['updated_at']);
            if($mongo_user = \Domain\Pusher\Models\User::find($user['id'])) {
                $this->info("Updating user with email {$user['email']}");
                $mongo_user->update($user);
                $mongo_user->profile()->update($profile);
            } else {
                $this->info("Creating user with email {$user['email']}");
                /** @var User $user */
                $user = \Domain\Pusher\Models\User::create($user);
                $user->profile()->save(
                    new Profile($profile)
                );
            }
        }
    }
}
