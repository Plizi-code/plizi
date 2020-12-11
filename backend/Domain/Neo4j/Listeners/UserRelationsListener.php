<?php


namespace Domain\Neo4j\Listeners;

use Carbon\Carbon;
use Domain\Neo4j\Models\User;

class UserRelationsListener
{

    /**
     * @param $event
     * @param $users
     */
    public function handle($event, $users) {
        [$sender, $recipient] = $users;
        if($event === 'friendships.accepted') {
            $sender = User::where('oid', $sender->id)->first();
            $receiver = User::where('oid', $recipient->id)->first();
            $sender->friends()->attach($receiver);
        } else if($event === 'friendships.cancelled') {
            $sender = User::where('oid', $sender->id)->first();
            $receiver = User::where('oid', $recipient->id)->first();
            $sender->friends()->detach($receiver);
        }
    }
}
