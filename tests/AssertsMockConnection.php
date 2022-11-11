<?php

namespace Tests;

use Illuminate\Support\Collection;
use Laragear\Surreal\Contracts\SurrealClient;
use Laragear\Surreal\JsonRpc\ClientMessage;
use Mockery\MockInterface;

trait AssertsMockConnection
{
    /** @var \Laragear\Surreal\Contracts\SurrealClient|\Mockery\MockInterface */
    protected $client;

    /** @var \Laragear\Surreal\SurrealConnection|\Mockery\MockInterface */
    protected $surreal;

    protected function setUpAssertsMockConnection(): void
    {
        $this->client = $this->mock(SurrealClient::class, static function (MockInterface $mock): void {
            $mock->expects('configure')->with([
                'driver' => 'ws',
                'ns' => 'forge',
                'db' => 'forge',
                'username' => 'forge',
                'password' => 'forge',
                'database' => 'rpc',
                'host' => 'localhost',
                'port' => 8000
            ]);
            $mock->expects('start')->andReturnNull();
        });

        $this->surreal = $this->getConnection('surreal');
    }

    protected function expectsMessage(string $statement, array $bindings = [], $return = new Collection())
    {
        $this->client->expects('send')->withArgs(function (ClientMessage $query) use ($statement, $bindings): bool {
            \dump([$query->params[0]->statement, $query->params[1]->parameters]);

            static::assertSame('query', $query->method);
            static::assertSame($statement, $query->params[0]->statement);
            static::assertSame($bindings, $query->params[1]->parameters);

            return true;
        })->andReturn($return);
    }
}
