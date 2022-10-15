<?php

namespace Tests;

use Laragear\Surreal\SurrealServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use function class_uses;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        foreach (class_uses($this) as $trait) {
            $method = 'setUp'.class_basename($trait);

            if (method_exists($this, $method)) {
                $this->{$method}();
            }
        }
    }

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
