<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Tests\Feature\TestApi;

class PostTest extends TestApi
{
    /**
     * Processed route: [GET] api/posts
     */
    public function testGetApiPosts()
    {
        $token = $this->getAuthToken();
        $this->json(
            'GET',
            '/api/posts',
            [],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Посты пользователя'
        )->assertStatus(200)
        ->assertJsonStructure(['data' => ['list']]);
    }

    /**
     * Processed route: [GET] api/user/posts
     */
    public function testGetApiUserPosts()
    {
        $token = $this->getAuthToken();
        $this->json(
            'GET',
            '/api/user/posts',
            [],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Посты на моей стене'
        )->assertStatus(200)
        ->assertJsonStructure(['data' => ['list']]);
    }

    /**
     * Processed route: [GET] api/user/{id}/posts
     */
    public function testGetApiUserIdPosts()
    {
        $token = $this->getAuthToken();
        $this->json(
            'GET',
            '/api/user/1/posts',
            [],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Посты пользователя по его ID'
        )->assertStatus(200)
        ->assertJsonStructure(['data' => ['list']]);
    }

    /**
     * Processed route: [GET] api/communities/{community_id}/posts
     */
    public function testGetApiCommunitiesPosts()
    {
        $token = $this->getAuthToken();
        $this->json(
            'GET',
            '/api/communities/1/posts',
            [],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Посты пользователя'
        )->assertStatus(200)
        ->assertJsonStructure(['data' => ['list']]);
    }

    /**
     * Processed route: [GET] api/posts/{id}
     */
    public function testGetApiPostsId()
    {
        $token = $this->getAuthToken();
        $this->json(
            'GET',
            '/api/posts/1',
            [],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Посты на моей стене'
        )->assertStatus(200)
        ->assertJsonStructure(['data']);
    }

    /**
     * Processed route: [POST] api/posts
     */
    public function testPostApiPosts()
    {
        $token = $this->getAuthToken();
        $this->json(
            'POST',
            '/api/posts',
            [
                'name' => 'Test post name',
                'body' => 'Test post body'
            ],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Посты пользователя'
        )->assertStatus(201)
        ->assertJsonStructure(['data' => ['id', 'name', 'body']]);
    }

    /**
     * Processed route: [POST] api/communities/{community_id}/posts
     */
    public function testPostApiCommunitiesPosts()
    {
        $testData = $this->getCommunityDataForTest();
        $token = $this->getAuthToken(false, User::find($testData->user_id)->email);
        $this->json(
            'POST',
            '/api/communities/' . $testData->community_id . '/posts',
            [
                'name' => 'Test post name',
                'body' => 'Test post body'
            ],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Посты пользователя'
        )->assertStatus(201)
        ->assertJsonStructure(['data']);
    }
}
