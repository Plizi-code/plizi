<?php


namespace Domain\Neo4j\Listeners;

use App\Events\CommunityCreated;
use App\Events\CommunityUpdated;
use App\Models\User;
use Carbon\Carbon;
use Domain\Neo4j\Models\Community as Neo4jCommunity;
use Domain\Neo4j\Models\User as Neo4jUser;

class CommunityListener
{
    /**
     * @param CommunityCreated|CommunityUpdated $event
     */
    public function handle($event)
    {
        $community = $event->community;
        $data = $community->toArray();
        $data['oid'] = $community->id;
        $data['created_at'] = new Carbon($community['created_at']);
        $data['updated_at'] = new Carbon($community['updated_at']);
        if (!$neo_community = Neo4jCommunity::where('oid', $data['oid'])->first()) {
            /** @var User $community */
            $neo_community = Neo4jCommunity::create($data);

            $this->members($neo_community, $community->users);
        }
    }

    /**
     * @param Neo4jCommunity $neo_community
     * @param User[] $users
     */
    private function members($neo_community, $users)
    {
        foreach ($users as $user) {
            /** @var Neo4jUser $member */
            $member = Neo4jUser::where('oid', $user->id)->first();
            $neo_community->members()->attach($member);
        }
    }
}
