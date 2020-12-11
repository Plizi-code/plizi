<?php


namespace Domain\Pusher\Listeners;

use App\Models\User;
use Carbon\Carbon;
use Domain\Pusher\Models\Profile;

class UserUpdateListener
{

    public function handle($event) {
        $user = $event->user->refresh();
        $user = $user->toArray();
        $profile = $user['profile'];
        $user = array_diff_key($user, array_flip(['profile']));
        $user['created_at'] = new Carbon($user['created_at']);
        $user['updated_at'] = new Carbon($user['updated_at']);
        $profile['created_at'] = new Carbon($profile['created_at']);
        $profile['updated_at'] = new Carbon($profile['updated_at']);
        /** @var User $mongo_user */
        if($mongo_user = \Domain\Pusher\Models\User::find($user['id'])) {
            $mongo_user->update($user);
            $mongo_user->profile->update($profile);
        } else {
            /** @var User $user */
            $user = \Domain\Pusher\Models\User::create($user);
            $user->profile()->save(
                new Profile($profile)
            );
        }
    }
}
