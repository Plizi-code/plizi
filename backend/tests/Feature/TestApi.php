<?php

namespace Tests\Feature;

use App\Models\User;
use Faker\Factory;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Model;

abstract class TestApi extends TestCase
{

    static $documentor;

    /**
     * Call the given URI with a JSON request.
     *
     * @param string $method
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @param string $apiMethodDescription
     * @return TestResponse
     */
    public function json($method, $uri, array $data = [], array $headers = [], string $apiMethodDescription = '')
    {
        $response = parent::json($method, $uri, $data, $headers);
        if (empty($apiMethodDescription)) {
            self::getDocumentor()->addTestResult(
                $this->getApiBlockDescription(),
                $apiMethodDescription,
                $method,
                $uri,
                $data,
                $headers,
                $response
            );
        }
        return $response;
    }

    protected function getApiBlockDescription(): string
    {
        $nameParts = explode("\\", static::class);
        return str_replace('Test', ' API', array_pop($nameParts));
    }

    protected static function getDocumentor(): TestApiDocumentor
    {
        if (self::$documentor === null) {
            self::$documentor = new TestApiDocumentor();
        }
        return self::$documentor;
    }

    protected function getAuthToken($returnAllResponse = false, $email = 'test@gmail.com', $password = 'secret')
    {
        $response = parent::json(
            'post',
            '/api/login',
            [
                'email' => $email,
                'password' => $password,
            ],
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Accept: application/json',
            ]
        );
        if ($returnAllResponse) {
            return $response;
        }
        $responseArr = json_decode($response->getContent(), true);
        if (!isset($responseArr['token'])) {
            var_dump($responseArr);
            die();
        }
        return $responseArr['token'];
    }

    /**
     * @return User
     */
    protected function createNewTestUser()
    {
        Model::unguard();
        $faker = Factory::create();
        $email = $faker->email;
        $user = User::create([
            'email' => $email,
            'password' => bcrypt('secret'),
            'token' => bcrypt('secret'),
            'last_activity_dt' => time(),
            'created_at' => time(),
            'updated_at' => time()
        ]);
        $user->profile()->create($this->createNewTestUserProfile());
        Model::reguard();
        return User::where('email', $email)->orderBy('id', 'desc')->first();
    }

    private function createNewTestUserProfile() {
        $faker = Factory::create('ru_RU');
        return [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'birthday' => $faker->dateTimeBetween('-70 years', '-20 years'),
            'city' => $faker->city,
            'sex' => $faker->randomElement(['n', 'm', 'f']),
            'user_pic' => $faker->imageUrl(640, 480, 'people', false),
        ];
    }

    protected function getCommunityDataForTest()
    {
        return \DB::table('community_members')->where('role', 'author')->first();
    }
}
