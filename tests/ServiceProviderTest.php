<?php

namespace Tests;

use Laragear\Surreal\Contracts\SurrealClient;
use Mockery\MockInterface;

class ServiceProviderTest extends TestCase
{
    public function test_stops_client_when_app_terminates(): void
    {
        $this->mock(SurrealClient::class, function (MockInterface $mock): void {
            $mock->expects('start')->andReturnNull();
            $mock->expects('stop')->andReturnNull();
        });

        $this->app->make('surreal.client')->start();

        $this->app->terminate();
    }
}
