<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\Feature\TestApi;

class GeoTest extends TestApi
{
    /**
     * Processed route: [GET] api/city/search
     * @return void
     */
    public function testGetApiCitySearch()
    {
        $token = $this->getAuthToken();
        /* @var TestResponse $response */
        $response = $this->json(
            'GET',
            '/api/city/search',
            [
                'search' => 'москв',
            ],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Search user city'
        );
        $response
            ->assertStatus(200);
    }
}
