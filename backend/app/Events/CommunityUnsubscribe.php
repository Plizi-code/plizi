<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class CommunityUnsubscribe
{
    use SerializesModels;

    /**
     * @var int
     */
    public $communityId;

    /**
     * @var int
     */
    public $userId;

    /**
     * CommunitySubscribe constructor.
     *
     * @param $communityId
     * @param $userId
     */
    public function __construct($communityId, $userId)
    {
        $this->communityId = $communityId;
        $this->userId = $userId;
    }
}
