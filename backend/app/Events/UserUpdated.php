<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;
use \Illuminate\Contracts\Auth\Authenticatable;

class UserUpdated
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
