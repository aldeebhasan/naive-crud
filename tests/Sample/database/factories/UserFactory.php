<?php

namespace Aldeebhasan\NaiveCrud\Test\Sample\Database\Factories;

use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'email' => fake()->safeEmail,
            'password' => Hash::make(fake()->words(3, true)),
        ];
    }
}
