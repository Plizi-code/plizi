<?php


namespace Domain\Neo4j\Listeners;

use App\Events\CommunityUnsubscribe;
use Domain\Neo4j\Models\Community as Neo4jCommunity;
use Domain\Neo4j\Models\User as Neo4jUser;

class CommunityUnsubscribeListener
{
    /**
     * @param CommunityUnsubscribe $event
     */
    public function handle($event)
    {
        $community = Neo4jCommunity::where('oid', $event->communityId)->first();
        $user = Neo4jUser::where('oid', $event->userId)->first();
        if ($community && $user) {
            $community->members()->detach($user);
        }
    }
}
