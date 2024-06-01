<?php

namespace Aldeebhasan\NaiveCrud\Test\Sample\Database\Factories;

use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\Blog;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogFactory extends Factory
{
    protected $model = Blog::class;

    public function definition(): array
    {
        return [
            'title' => fake()->text(20),
            'description' => fake()->text,
            'user_id' => User::factory(),
            'image' => fake()->imageUrl,
        ];
    }
}
