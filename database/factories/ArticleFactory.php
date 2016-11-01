<?php

$factory->define(App\Models\Article::class, function (Faker\Generator $faker) {

    return [
        'article_group_id' => App\Models\ArticleGroup::all()->random(1)->id,
        'title' => $faker->realText(50),
        'summary' => $faker->paragraph(2),
        'body' => $faker->paragraph(5),
    ];
});