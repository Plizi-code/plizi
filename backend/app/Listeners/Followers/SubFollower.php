<?php

namespace App\Listeners\Followers;

use App\Models\User;

class SubFollower
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        /** @var User $user */
        $user = $event->user;
        $user->profile()->decrement('follower_count');
        $user->save();
    }
}
