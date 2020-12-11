<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Profile;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Profile::class, function (Faker $faker) {
    return [

        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'birthday' => $faker->date($format = 'Y-m-d', $max = '2012',$min = '1990'),
        'city' => $faker->city,
    ];
});
