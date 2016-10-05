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

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => str_random(10),
        'gender' => rand(0, 1),
        'avatar_link' => null,
        'remember_token' => str_random(10),
    ];
});
$factory->define(App\Models\Review::class, function (Faker\Generator $faker) {
    return [
        'content' => $faker->paragraph(20),
        'user_id' => function () {
            return App\Models\User::inRandomOrder()->first()->id;
        },
        'book_id' => function () {
            return App\Models\Book::inRandomOrder()->first()->id;
        },
    ];
});
$factory->define(App\Models\Comment::class, function (Faker\Generator $faker) {
    return [
        'content' => $faker->sentence,
        'user_id' => function () {
            return App\Models\User::inRandomOrder()->first()->id;
        },
        'review_id' => function () {
            return App\Models\Review::inRandomOrder()->first()->id;
        },
    ];
});
$factory->define(App\Models\Book::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence,
        'author' => $faker->name,
        'num_page' => $faker->numberBetween(100, 500),
        'book_image' => null,
        'description' => $faker->paragraph(20),
        'category_id' => function () {
            return App\Models\Category::inRandomOrder()->first()->id;
        },
        'num_favorite' => 0,
        'avg_rate_point' => 0,
        'published_at' => $faker->date('d-m-Y'),
    ];
});
$factory->define(App\Models\Category::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->words(rand(1, 4), true),
        'category_parent_id' => function () {
            $c = App\Models\Category::inRandomOrder()->first();
            $p = rand(0, 1);
            if ($c && $p) {
                return $c->id;
            }
            return null;
        },
    ];
});
$factory->define(App\Models\Rate::class, function (Faker\Generator $faker) {
    return [
        'point' => rand(0, 5),
        'user_id' => function () {
            return App\Models\User::inRandomOrder()->first()->id;
        },
        'book_id' => function () {
            return App\Models\Book::inRandomOrder()->first()->id;
        },
    ];
});
