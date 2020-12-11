<?php

namespace App\Events\Followers;

use App\Models\User;
use Illuminate\Queue\SerializesModels;

class AddFollower
{
    use SerializesModels;

    /**
     * User model
     *
     * @var User
     */
    public $user;

    /**
     * UserUpdated constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
