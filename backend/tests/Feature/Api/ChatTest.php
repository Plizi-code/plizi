<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\Feature\TestApi;

class ChatTest extends TestApi
{
    /**
     * Processed route: [GET] api/chat/dialogs
     */
    public function testGetApiChatDialogs()
    {
        $token = $this->getAuthToken();
        /* @var TestResponse $response */
        $this->json(
            'GET',
            '/api/chat/dialogs',
            [],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Возвращает список диалогов'
        )->assertStatus(200)
        ->assertJsonStructure(['list']);
    }

    /**
     * Processed route: [GET] api/chat/messages/{chat_id}
     */
    public function testGetApiChatMessages()
    {
        $token = $this->getAuthToken();
        /* @var TestResponse $response */
        $this->json(
            'GET',
            '/api/chat/messages/1',
            [],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Возвращает список сообщений'
        )->assertStatus(200)
        ->assertJsonStructure(['list']);
    }

    /**
     * Processed route: [POST] api/chat/send
     */
    public function testGetApiChatSend()
    {
        $token = $this->getAuthToken();
        /* @var TestResponse $response */
        $this->json(
            'POST',
            '/api/chat/send',
            [
                'chat_id' => 1,
                'body' => 'Lorem ipsum'
            ],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Отправка сообщения пользователю'
        )->assertStatus(200)
        ->assertJson(['status' => 'OK']);
    }
}
