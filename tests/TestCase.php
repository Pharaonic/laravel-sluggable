<?php

namespace Pharaonic\Laravel\Sluggable\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Pharaonic\Laravel\Sluggable\SluggableServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [SluggableServiceProvider::class];
    }

}
