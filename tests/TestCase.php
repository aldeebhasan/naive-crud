<?php

namespace Aldeebhasan\NaiveCrud\Test;

use Aldeebhasan\NaiveCrud\NaiveCrudProvider;
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
        return [NaiveCrudProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        

    }
}
