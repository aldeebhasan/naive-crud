<?php

namespace Aldeebhasan\NaiveCrud\Test;

use Aldeebhasan\NaiveCrud\NCProvider;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__.'/Sample/database/migrations');
        $this->loadFactoriesUsing($this->app, __DIR__.'/Sample/database/factories');
        $this->app->get('router')->middleware('api')->group(__DIR__.'/Sample/routes/api.php');
    }

    protected function getPackageProviders($app): array
    {
        return [NCProvider::class];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('naive-crud.image_thumbnail', true);
        $app['config']->set('app.debug', true);
        $app['config']->set('auth.guards.api', [
            'driver' => 'token',
            'provider' => 'users',
        ]);

    }
}
