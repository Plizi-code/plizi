<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Domain\Neo4j\Service\UserService;

class NeoController extends Controller
{
    public $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function create()
    {
        $this->userService->getFriends('5eb4fe2ff7d5263a37394061');
    }

}
