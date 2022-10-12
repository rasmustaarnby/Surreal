<?php

namespace Tests;

use Laragear\Surreal\SurrealServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [SurrealServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app->make('config')->set([
            'database.connections.surreal' => [
                'driver' => 'surreal',
                'url' => 'ws://localhost:8000/rpc',
                'ns' => 'forge',
                'db' => 'forge',
                'username' => 'forge',
                'password' => 'forge',
            ]
        ]);
    }
}