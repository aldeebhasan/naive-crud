<?php

use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\Blog;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\Comment;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\User;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'content' => $faker->text,
        'user_id' => factory(User::class),
        'blog_id' => factory(Blog::class),
        'image' => $faker->imageUrl,
    ];
});
