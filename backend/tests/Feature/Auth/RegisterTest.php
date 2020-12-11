<?php

namespace Tests\Feature\Auth;

use Tests\Feature\TestApi;

class RegisterTest extends TestApi
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUserRegister()
    {
        $email = 'wenfownfowefn' . rand() . '@gmail.com';
        //$token = $this->getAuthToken();
        $response = $this->json(
            'POST',
            '/api/register',
            [
                'email' => $email,
                'firstName' => 'Newname',
                'lastName' => 'Newlastname',
                'birthday' => '1991-01-01'
            ],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
            ],
            'Test User Register method'
        );

        $response
            ->assertStatus(201)
            ->assertJson(
                [
                    'message' => 'Please confirm email',
                    'email' => $email,
                ]
            );
    }
}
