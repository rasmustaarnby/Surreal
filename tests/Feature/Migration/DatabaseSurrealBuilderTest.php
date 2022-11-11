<?php

namespace Tests\Feature\Migration;

use Laragear\Surreal\Schema\SurrealSchemaBuilder;
use Laragear\Surreal\Schema\SurrealSchemaGrammar;
use Laragear\Surreal\SurrealConnection;
use Mockery;
use Tests\TestCase;

class DatabaseSurrealBuilderTest extends TestCase
{
    public function test_create_database(): void
    {
        $grammar = new SurrealSchemaGrammar();

        $connection = Mockery::mock(SurrealConnection::class);

        $connection->expects('getSchemaGrammar')->andReturn($grammar);

        $connection->shouldReceive('statement')->once()->with(
            'DEFINE NAMESPACE "my_temporary_database"'
        )->andReturn(true);

        $builder = new SurrealSchemaBuilder($connection);

        $builder->createDatabase('my_temporary_database');
    }

    public function test_drop_database_if_exists(): void
    {
        $grammar = new SurrealSchemaGrammar();

        $connection = Mockery::mock(SurrealConnection::class);

        $connection->expects('getSchemaGrammar')->andReturn($grammar);

        $connection->shouldReceive('statement')->once()->with(
            'REMOVE DATABASE "my_database_a"'
        )->andReturn(true);

        $builder = new SurrealSchemaBuilder($connection);

        $builder->dropDatabaseIfExists('my_database_a');
    }
}
