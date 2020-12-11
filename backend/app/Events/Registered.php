<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use \Illuminate\Contracts\Auth\Authenticatable;

class Registered
{
    use SerializesModels;

    /**
     * The authenticated user.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $user;

    /**
     * Raw password
     * @var
     */
    public $rawPassword;

    /**
     * Registered constructor.
     *
     * @param Authenticatable $user
     * @param $rawPassword
     */
    public function __construct(Authenticatable $user, $rawPassword)
    {
        $this->user = $user;
        $this->rawPassword = $rawPassword;
    }
}
