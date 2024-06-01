<?php

namespace Aldeebhasan\NaiveCrud\Test\Feature;

use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\User;
use Aldeebhasan\NaiveCrud\Test\TestCase;

class FeatureTestCase extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withHeader('accept', 'application/json');
    }

    protected function login(User $user = null, $driver = 'api')
    {
        $this->user = $user ?? User::factory()->create();
        $this->actingAs($this->user, $driver);
    }
}
