<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\Feature\TestApi;

class UserBlacklistTest extends TestApi
{
    /**
     * Processed route: [POST] api/user/blacklist
     */
    public function testPostApiUserBlacklist()
    {
        $token = $this->getAuthToken();
        /* @var TestResponse $response */
        $response = $this->json(
            'POST',
            '/api/user/blacklist',
            [
                'user_id' => 2,
            ],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Add user to blacklist'
        );
        $response
            ->assertStatus(200)
            ->assertJson(['message' => 'Added']);
    }

    /**
     * Processed route: [DELETE] api/user/blacklist
     */
    public function testDeleteApiUserBlacklist()
    {
        $token = $this->getAuthToken();
        /* @var TestResponse $response */
        $response = $this->json(
            'DELETE',
            '/api/user/blacklist',
            [
                'user_id' => 2,
            ],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Delete user from blacklist'
        );
        $response
            ->assertStatus(200)
            ->assertJson(['message' => 'Deleted']);
    }
}
