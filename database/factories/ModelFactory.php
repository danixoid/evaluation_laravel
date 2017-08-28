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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'iin' => $faker->unique()->numerify('00000000####'),
        'password' => $password ?: $password = bcrypt('12345'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Competence::class, function (Faker\Generator $faker) {

    $type = \App\CompetenceType::whereProf(true)->firstOrFail();

    return [
        'name' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'note' => $faker->text($maxNbChars = 200),
        'competence_type_id' => $type->id,
    ];
});

$factory->define(App\Indicator::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->text($maxNbChars = 200),
    ];
});
