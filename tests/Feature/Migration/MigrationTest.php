<?php

namespace Tests\Feature\Migration;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Laragear\Surreal\SurrealConnection;
use Mockery;
use Tests\TestCase;

class MigrationTest extends TestCase
{
    public function test_migrates_native_tables(): void
    {
        $connection = Mockery::mock(SurrealConnection::class);

        $connection->expects('getConfig')->with('prefix_indexes')->andReturnFalse();

        Schema::connection('surreal')
            ->setConnection($connection)
            ->create('table', static function (Blueprint $table): void {
                $table->any('any_column');
                $table->array('array_column');
                $table->boolean('bool_column');
                $table->datetime('datetime_column');
                $table->decimal('decimal_column');
                $table->duration('duration_column');
                $table->float('float_column');
                $table->number('number_column');
                $table->object('object_column');
                $table->record('record_column');
                $table->geometry('geometry_column');
            });
    }

    public function test_migrates_integer_columns(): void
    {

    }

    public function test_migrates_string_columns(): void
    {

    }
}
