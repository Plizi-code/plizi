<?php

namespace Tests\Feature\Auth;

use Tests\Feature\TestApi;

class LoginTest extends TestApi
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUserLogin()
    {
        $response = $this->getAuthToken(true);
        $response->assertStatus(200);
    }

    /**
     * Processed route: [GET] api/login/{provider}
     * Processed route: [GET] api/login/{provider}/callback
     * Processed route: [POST] api/sociallogin/{provider}
     */
}
