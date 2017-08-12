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

$factory->define(App\Models\Page::class, function (Faker\Generator $faker) {
    return [
        'slug' => $faker->slug,
        'title' => $faker->catchPhrase,
        'content' => $faker->text,
        'published_content' => $faker->text,
        'meta_title' => $faker->catchPhrase,
        'meta_desc' => $faker->sentence,
        'meta_keywords' => '',
        'menu' => false,
        'parent_id' => NULL,
        'order' => 999,
        'header_image_id' => NULL,
        'pagehits' => 0,
    ];
});
