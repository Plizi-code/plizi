<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\Feature\TestApi;

class ProfileTest extends TestApi
{

    /**
     * Processed route: [GET] api/user
     */
    public function testGetApiUser()
    {
        $token = $this->getAuthToken();
        /* @var TestResponse $response */
        $this->json(
            'GET',
            '/api/user',
            [],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Get current user'
        )   ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        'id',
                        'email',
                        'profile',
                        'privacySettings',
                    ],
                    'channel',
                ]
            );
    }

    /**
     * Processed route: [GET] api/user/{user}
     */
    public function testGetApiUserId()
    {
        $token = $this->getAuthToken();
        /* @var TestResponse $response */
        $this->json(
            'GET',
            '/api/user/2',
            [],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Get user by ID'
        )   ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        'id',
                        'profile',
                    ],
                ]
            );
    }

    /**
     * Processed route: [PATCH] api/user
     * @return void
     */
    public function testPatchApiUser()
    {
        $token = $this->getAuthToken();
        $this->json(
            'PATCH',
            '/api/user',
            [
                'first_name' => 'Newname',
                'last_name' => 'Newlastname',
            ],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Update user'
        )->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        'firstName',
                        'lastName',
                        'sex',
                        'birthday',
                    ],
                ]
            );
    }

    /**
     *
     * Processed route: [GET] api/user/communities
     */
    public function testGetApiUserCommunities()
    {
        $token = $this->getAuthToken();
        /* @var TestResponse $response */
        $this->json(
            'GET',
            '/api/user/communities',
            [],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Get current user communities'
        )   ->assertStatus(200)
            ->assertJsonStructure(['data']);
    }
}
