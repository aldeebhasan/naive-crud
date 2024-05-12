<?php

use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\Blog;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\User;
use Faker\Generator as Faker;

$factory->define(Blog::class, function (Faker $faker) {
    return [
        'title' => $faker->text(10),
        'description' => $faker->text,
        'user_id' => factory(User::class),
        'image' => $faker->imageUrl,
    ];
});
