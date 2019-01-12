<?php

use Faker\Generator as Faker;

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

$factory->define(App\Model\Comment::class, function (Faker $faker) {

    return [
        'content' => $faker->text(1000),
        'post_id' => rand(1, 10),
        'user_id' => rand(1, 10)
    ];
});
