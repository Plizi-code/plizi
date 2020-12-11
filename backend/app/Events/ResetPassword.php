<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;

class ResetPassword
{
    use SerializesModels;

    /**
     * User model
     *
     * @var User
     */
    public $user;

    /**
     * User raw password
     *
     * @var string
     */
    public $password;

    /**
     * ResetPassword constructor.
     *
     * @param User $user
     * @param $password
     */
    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }
}
