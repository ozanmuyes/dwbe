<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/**
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */

$factory->define(App\TokenUser::class, function (Faker\Generator $faker) {
    return [
        // NOTE No 'id' field
        'role' => 'user', // This role (`user`) is the least privileged user role
//        'username' => $faker->userName,
    ];
});

$factory->defineAs(App\TokenUser::class, 'admin', function () use ($factory) {
    return array_merge(
        $factory->raw(App\TokenUser::class),
        ['role' => 'admin']
    );
});

// NOTE Add other TokenUser roles here (e.g. 'mod', 'dev' etc.)


$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'username' => $faker->userName,
        'email' => $faker->email,
        'password' => $faker->password(),
    ];
});
