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

$factory->define(App\Models\Media::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word.".".$faker->fileExtension,
        'description' => $faker->sentence(20, true),
        'path' => $faker->word."/".$faker->word.".".$faker->fileExtension,
        'mime' => $faker->mimeType,
        'extension' => $faker->fileExtension
    ];
});
