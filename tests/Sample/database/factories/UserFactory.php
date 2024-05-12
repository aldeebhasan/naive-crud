<?php

use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => Hash::make($faker->words(3, true)),
    ];
});
