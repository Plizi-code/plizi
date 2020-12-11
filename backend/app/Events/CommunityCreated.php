<?php

namespace App\Events;

use App\Models\Community;
use Illuminate\Queue\SerializesModels;

class CommunityCreated
{
    use SerializesModels;

    /**
     * Community model
     *
     * @var Community
     */
    public $community;

    /**
     * UserCreated constructor.
     *
     * @param Community $community
     */
    public function __construct(Community $community)
    {
        $this->community = $community;
    }
}
