<?php

namespace Tests\Feature\Api;

use Hootlex\Friendships\Models\Friendship;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\Feature\TestApi;

class UserTest extends TestApi
{
    /**
     * Processed route: [GET] api/user/search
     */
    public function testPostApiUserSearch()
    {
        $token = $this->getAuthToken();
        /* @var TestResponse $response */
        $r = $this->json(
            'GET',
            '/api/user/search/admin',
            [],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Поиск пользователя'
        )->assertStatus(200)
        ->assertJsonStructure(['data' => ['list']]);
    }

    /**
     * Processed route: [GET] api/user/notifications
     */
    public function testGetApiUserNotifications()
    {
        $token = $this->getAuthToken();
        /* @var TestResponse $response */
        $r = $this->json(
            'GET',
            '/api/user/notifications',
            [],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Получить все сообщения пользователя'
        )->assertStatus(200)
        ->assertJsonStructure(['data' => ['list']]);
    }

    /**
     * Processed route: [GET] api/user/friendship
     */
    public function testGetApiUserFriendship()
    {
        $token = $this->getAuthToken();
        /* @var TestResponse $response */
        $this->json(
            'GET',
            '/api/user/friendship',
            [],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Получить моих друзей'
        )->assertStatus(200)
        ->assertJsonStructure(['data' => ['list']]);
    }

    /**
     * Processed route: [GET] api/user/{id}/friendship
     */
    public function testGetApiUserIdFriendship()
    {
        $token = $this->getAuthToken();
        /* @var TestResponse $response */
        $this->json(
            'GET',
            '/api/user/2/friendship',
            [],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Получить друзей пользователя'
        )->assertStatus(200)
        ->assertJsonStructure(['data' => ['list']]);
    }

    /**
     * Processed route: [POST] api/user/friendship
     */
    public function testPostApiUserFriendship()
    {
        \DB::table('friendships')
            ->where('sender_id', 1)
            ->orWhere('recipient_id', 1)
            ->delete();
        $token = $this->getAuthToken();
        /* @var TestResponse $response */
        $this->json(
            'POST',
            '/api/user/friendship',
            [
                'userId' => 2,
            ],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Отправить запрос на добавление в друзья'
        )->assertStatus(200)
        ->assertJson(['message' => 'Запрос на добавление в друзья отправлен']);
    }

    /**
     * Processed route: [POST] api/user/friendship/accept
     */
    public function testPostApiUserFriendshipAccept()
    {
        Friendship::create([
            'sender_type' => 'App\Models\User',
            'sender_id' => '2',
            'recipient_type' => 'App\Models\User',
            'recipient_id' => '1',
            'status' => '0',
        ]);
        $token = $this->getAuthToken();
        /* @var TestResponse $response */
        $this->json(
            'POST',
            '/api/user/friendship/accept',
            [
                'userId' => 2,
            ],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Принять запрос на добавление в друзья'
        )->assertStatus(200)
        ->assertJson(['message' => 'Вы приняли пользователя в друзья']);
        \DB::table('friendships')
            ->where('sender_id', 1)
            ->orWhere('recipient_id', 1)
            ->delete();
    }

    /**
     * Processed route: [POST] api/user/friendship/decline
     */
    public function testPostApiUserFriendshipDecline()
    {
        Friendship::create([
            'sender_type' => 'App\Models\User',
            'sender_id' => '2',
            'recipient_type' => 'App\Models\User',
            'recipient_id' => '1',
            'status' => '0',
        ]);
        $token = $this->getAuthToken();
        /* @var TestResponse $response */
        $this->json(
            'POST',
            '/api/user/friendship/decline',
            [
                'userId' => 2,
            ],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Отклонить запрос на добавление в друзья'
        )->assertStatus(200)
        ->assertJson(['message' => 'Вы отклонили запрос на добавление в друзья от пользователя']);
        \DB::table('friendships')
            ->where('sender_id', 1)
            ->orWhere('recipient_id', 1)
            ->delete();
    }
}
