<?php

namespace Tests;

use Carbon\CarbonInterval;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\LazyCollection;
use Laragear\Surreal\Contracts\SurrealClient;
use Laragear\Surreal\Events\StatementPrepared;
use Laragear\Surreal\JsonRpc\QueryMessage;
use Laragear\Surreal\Query\SurrealGrammar;
use Laragear\Surreal\Query\SurrealProcessor;
use Laragear\Surreal\Schema\SurrealSchemaBuilder;
use Laragear\Surreal\Schema\SurrealSchemaGrammar;
use Laragear\Surreal\Schema\SurrealSchemaState;
use Laragear\Surreal\SurrealConnection;
use Mockery;
use Mockery\MockInterface;
use function iterator_to_array;

class SurrealConnectionTest extends TestCase
{
    protected function createConnection(): SurrealConnection
    {
        $this->mock(SurrealClient::class, function (MockInterface $mock): void {
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

        return DB::connection('surreal');
    }

    public function test_uses_default_query_grammar_and_post_processor(): void
    {
        $connection = $this->createConnection();

        static::assertInstanceOf(SurrealGrammar::class, $connection->getQueryGrammar());
        static::assertInstanceOf(SurrealProcessor::class, $connection->getPostProcessor());
    }

    public function test_uses_default_schema_tools(): void
    {
        $builder = $this->createConnection()->getSchemaBuilder();

        static::assertInstanceOf(SurrealSchemaBuilder::class, $builder);
        static::assertInstanceOf(SurrealSchemaGrammar::class, $builder->getConnection()->getSchemaGrammar());
        static::assertInstanceOf(SurrealSchemaState::class, $builder->getConnection()->getSchemaState());
    }

    public function test_sets_client(): void
    {
        $connection = $this->createConnection();

        $client = Mockery::mock(SurrealClient::class);

        $connection->setClient($client);

        static::assertSame($client, $connection->getClient());
    }

    public function test_runs_select(): void
    {
        $event = Event::fake(StatementPrepared::class);

        $connection = $this->createConnection();

        $client = $connection->getClient();

        $client->expects('send')->withArgs(function (QueryMessage $query): bool {
            static::assertSame('query', $query->method);
            static::assertSame('foo', $query->params[0]->statement);
            static::assertSame(['foo'], $query->params[0]->bindingKeys);
            static::assertSame(['foo' => 'bar'], $query->params[1]->parameters);

            return true;
        })
        ->andReturn($expected = new Collection(['baz' => 'quz']));

        $result = $connection->select('foo', ['foo' => 'bar']);

        static::assertSame($expected, $result);

        $event->assertDispatched(StatementPrepared::class);
    }

    public function test_runs_statement(): void
    {
        $event = Event::fake(StatementPrepared::class);

        $connection = $this->createConnection();

        $client = $connection->getClient();

        $client->expects('send')->withArgs(function (QueryMessage $query): bool {
            static::assertSame('query', $query->method);
            static::assertSame('foo', $query->params[0]->statement);
            static::assertSame(['foo'], $query->params[0]->bindingKeys);
            static::assertSame(['foo' => 'bar'], $query->params[1]->parameters);

            return true;
        })
        ->andReturn($expected = new Collection(['baz' => 'quz']));

        $result = $connection->statement('foo', ['foo' => 'bar']);

        static::assertSame($expected, $result);

        $event->assertDispatched(StatementPrepared::class);
    }

    public function test_runs_cursor(): void
    {
        $event = Event::fake(StatementPrepared::class);

        $connection = $this->createConnection();

        $client = $connection->getClient();

        $client->expects('send')->withArgs(function (QueryMessage $query, bool $async): bool {
            static::assertSame('query', $query->method);
            static::assertSame('foo', $query->params[0]->statement);
            static::assertSame(['foo'], $query->params[0]->bindingKeys);
            static::assertSame(['foo' => 'bar'], $query->params[1]->parameters);
            static::assertTrue($async);

            return true;
        })
        ->andReturn(new LazyCollection(['quz']));

        $result = $connection->cursor('foo', ['foo' => 'bar']);

        static::assertSame(['quz'], iterator_to_array($result));

        $event->assertDispatched(StatementPrepared::class);
    }

    public function test_prepares_binding(): void
    {
        $connection = $this->createConnection();

        $bindings = $connection->prepareBindings([
            'foo' => 'bar',
            0 => 1,
            2 => true,
            'date' => Date::create(2020),
            'interval' => CarbonInterval::create(1, 2, 3, 4, 5, 6, 7, 8),
        ]);

        static::assertSame([
            'foo' => 'bar',
            0 => 1,
            2 => true,
            'date' => '2020-01-01 00:00:00',
            'interval' => '1y3w4d5h2m7s8µs'
        ], $bindings);
    }
}
