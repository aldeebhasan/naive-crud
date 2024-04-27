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
    }

    protected function getPackageProviders($app)
    {
        return [NCProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('naive-crud.image_thumbnail', true);

    }
}
