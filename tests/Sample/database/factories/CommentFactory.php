<?php

namespace Aldeebhasan\NaiveCrud\Test\Sample\Database\Factories;

use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\Blog;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\Comment;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'content' => fake()->text(25),
            'user_id' => User::factory(),
            'blog_id' => Blog::factory(),
            'active' => true,
        ];
    }
}
