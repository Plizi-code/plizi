<?php

namespace App\Traits;

use Domain\Neo4j\Service\UserService;
use Hootlex\Friendships\Models\Friendship;
use Hootlex\Friendships\Models\FriendFriendshipGroups;
use Hootlex\Friendships\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;

use Hootlex\Friendships\Traits\Friendable as HootlexFriendable;

trait Friendable
{
    use HootlexFriendable;

    /**
     * @param Model $recipient
     * @return bool|Friendship
     */
    public function befriend(Model $recipient)
    {
        if (!$this->canBefriend($recipient)) {
            return false;
        }

        $friendship = (new Friendship)->fillRecipient($recipient)->fill([
            'status' => Status::PENDING,
        ]);

        $this->friends()->save($friendship);

        Event::dispatch('friendships.sent', [$this, $recipient]);

        return $friendship;
    }

    /**
     * @param Model $recipient
     * @return mixed
     */
    public function unfriend(Model $recipient)
    {
        $deleted = $this->findFriendship($recipient)->delete();

        Event::dispatch('friendships.cancelled', [$this, $recipient]);

        return $deleted;
    }

    /**
     * @param Model $recipient
     * @return mixed
     */
    public function acceptFriendRequest(Model $recipient)
    {
        $updated = $this->findFriendship($recipient)->whereRecipient($this)->update([
            'status' => Status::ACCEPTED,
        ]);

        Event::dispatch('friendships.accepted', [$this, $recipient]);

        return $updated;
    }

    /**
     * @param Model $recipient
     * @return mixed
     */
    public function denyFriendRequest(Model $recipient)
    {
        $updated = $this->findFriendship($recipient)->whereRecipient($this)->update([
            'status' => Status::DENIED,
        ]);

        Event::dispatch('friendships.denied', [$this, $recipient]);

        return $updated;
    }

    /**
     * @param Model $recipient
     * @return Friendship
     */
    public function blockFriend(Model $recipient)
    {
        // if there is a friendship between the two users and the sender is not blocked
        // by the recipient user then delete the friendship
        if (!$this->isBlockedBy($recipient)) {
            $this->findFriendship($recipient)->delete();
        }

        $friendship = (new Friendship)->fillRecipient($recipient)->fill([
            'status' => Status::BLOCKED,
        ]);

        $this->friends()->save($friendship);

        Event::dispatch('friendships.blocked', [$this, $recipient]);

        return $friendship;
    }

    /**
     * @param Model $recipient
     * @return mixed
     */
    public function unblockFriend(Model $recipient)
    {
        $deleted = $this->findFriendship($recipient)->whereSender($this)->delete();

        Event::dispatch('friendships.unblocked', [$this, $recipient]);

        return $deleted;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getFriends($limit = 50, $offset = 0)
    {
        $neo4UserService = new UserService();
        return $neo4UserService->getFriends($this->id, auth()->id(), $limit, $offset);
    }

    /**
     * @param $user_id
     * @return bool
     */
    public function isFriendOfFriendWith($user_id) {
        $neo4UserService = new UserService();
        return $neo4UserService->isFriendOfFriendWith($this->id, $user_id);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getFriendsOfFriends($limit = 50, $offset = 0)
    {
        $neo4UserService = new UserService();
        return $neo4UserService->getFriendsOfFriends($this->id, $limit, $offset);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getRecommendedFriends($limit = 50, $offset = 0)
    {
        $neo4UserService = new UserService();
        return $neo4UserService->getRecommendedFriends($this->id, $limit, $offset);
    }
}
