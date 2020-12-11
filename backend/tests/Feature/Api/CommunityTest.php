<?php

namespace Tests\Feature\Api;

use App\Models\Community;
use App\Models\User;
use Tests\Feature\TestApi;

class CommunityTest extends TestApi
{

    /**
     * Processed route: [PATCH] api/communities/{id}
     */
    public function testPatchApiCommunities()
    {
        $testData = $this->getCommunityDataForTest();
        $token = $this->getAuthToken(false, User::find($testData->user_id)->email);
        $this->json(
            'PATCH',
            '/api/communities/' . $testData->community_id,
            [
                'name' => 'Test name',
                'description' => 'Test description',
                'notice' => 'Test notive',
                'website' => 'http://www.uvarova.ru/rerum-ut-ipsum-tenetur-et-ipsum',
                'location' => 'Test location',
            ],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Обновить сообщество'
        )->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id', 'name', 'description', 'notice', 'primaryImage', 'url', 'website', 'location'
            ]
        ]);
    }

    /**
     * Processed route: [POST] api/communities
     */
    public function testPostApiCommunities()
    {
        $token = $this->getAuthToken();
        $this->json(
            'POST',
            '/api/communities',
            [
                'name' => 'Test name',
                'description' => 'Test description',
                'notice' => 'Test notive',
                'website' => 'http://www.uvarova.ru/rerum-ut-ipsum-tenetur-et-ipsum',
                'location' => 'Test location',
            ],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Создать сообщество'
        )->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id', 'name', 'description', 'notice', 'primaryImage', 'url', 'website', 'location'
            ]
        ]);
    }

    /**
     * Processed route: [GET] api/communities
     */
    public function testGetApiCommunities()
    {
        $token = $this->getAuthToken();
        $this->json(
            'GET',
            '/api/communities',
            [],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Создать сообщество'
        )->assertStatus(200)
        ->assertJsonStructure(['data']);
    }

    /**
     * Processed route: [GET] api/communities/{id}
     */
    public function testGetApiCommunity()
    {
        $testData = $this->getCommunityDataForTest();
        $token = $this->getAuthToken(false, User::find($testData->user_id)->email);
        $this->json(
            'GET',
            '/api/communities/' . $testData->community_id,
            [],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Список сообществ в которых я учавствую'
        )->assertStatus(200)
        ->assertJsonStructure(['data']);
    }

    /**
     * Processed route: [GET] api/communities/{id}/subscribe
     */
    public function testGetApiCommunitiesSubscribe()
    {
        $user = $this->createNewTestUser();
        $community = $this->getCommunityForTest();
        $token = $this->getAuthToken(false, $user->email);
        $this->json(
            'GET',
            '/api/communities/' . $community->id . '/subscribe',
            [],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Подписаться на сообщество'
        )->assertStatus(200)
        ->assertJson([
            'message' => 'Вы были успешно добавлены в сообщество',
            'id' => $community->id
        ]);
    }

    /**
     * Processed route: [GET] api/communities/{id}/unsubscribe
     */
    public function testGetApiCommunitiesUnsubscribe()
    {
        $community = $this->getCommunityForTest();
        $token = $this->getAuthToken(false, $community->users()->first()->email);
        $this->json(
            'GET',
            '/api/communities/' . $community->id . '/unsubscribe',
            [],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: no-cache',
                'Authorization' => 'Bearer ' . $token . '',
            ],
            'Отписаться от сообщества'
        )->assertStatus(200)
            ->assertJson([
                'message' => 'Вы успешно отписались из данного сообщества',
                'id' => $community->id
            ]);
    }

    /**
     * @return Community
     */
    protected function getCommunityForTest()
    {
        return Community::first();
    }
}
