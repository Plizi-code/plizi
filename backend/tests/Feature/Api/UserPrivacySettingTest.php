<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\Feature\TestApi;

class UserPrivacySettingTest extends TestApi
{
    /**
     * Processed route: [PATCH] api/user/privacy
     * @return void
     */
    public function testPatchUserPrivacy()
    {
        $token = $this->getAuthToken();
        /* @var TestResponse $response */
        $response = $this->json(
            'GET',
            '/api/user',
            [],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Test User Privacy settings method 1'
        );
        $response
            ->assertStatus(200)
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
}
