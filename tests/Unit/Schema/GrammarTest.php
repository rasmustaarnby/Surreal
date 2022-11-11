<?php

namespace Tests\Unit\Schema;

use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Laragear\Surreal\Schema\Blueprint as SurrealBlueprintMacros;
use Laragear\Surreal\Schema\SurrealSchemaGrammar;
use Mockery;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @see https://github.com/laravel/framework/blob/9.x/tests/Database/DatabaseSQLiteSchemaGrammarTest.php
 * @see https://github.com/laravel/framework/blob/9.x/tests/Database/DatabaseMySqlSchemaGrammarTest.php
 */
class GrammarTest extends TestCase
{
    protected function setUp(): void
    {
        Blueprint::mixin(new SurrealBlueprintMacros());
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    protected function getConnection()
    {
        return Mockery::mock(Connection::class);
    }

    public function getGrammar()
    {
        return new SurrealSchemaGrammar();
    }

    public function test_basic_create_table(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->create();
        $blueprint->integer('id');
        $blueprint->string('email');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        static::assertCount(3, $statements);
        static::assertSame([
            'DEFINE TABLE "users" SCHEMAFULL',
            'DEFINE FIELD "id" ON "users" TYPE int',
            'DEFINE FIELD "email" ON "users" TYPE string',
        ], $statements);

        $blueprint = new Blueprint('users');
        $blueprint->integer('id');
        $blueprint->string('email');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(2, $statements);
        $expected = [
            'DEFINE FIELD "id" ON "users" TYPE int',
            'DEFINE FIELD "email" ON "users" TYPE string',
        ];
        $this->assertEquals($expected, $statements);
    }

    public function test_drop_table(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->drop();

        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        static::assertSame(['REMOVE TABLE "users"'], $statements);
    }

    public function test_drop_table_if_exists(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->dropIfExists();

        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        static::assertSame(['REMOVE TABLE "users"'], $statements);
    }

    public function test_drop_unique_index(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->dropUnique('index');

        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        static::assertSame(['REMOVE INDEX "index" ON TABLE "users"'], $statements);
    }

    public function test_drop_index(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->dropIndex('index');

        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        static::assertSame(['REMOVE INDEX "index" ON TABLE "users"'], $statements);
    }

    public function test_drop_column(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->dropColumn('name');

        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        static::assertSame(['REMOVE FIELD "name" ON TABLE "users"'], $statements);
    }

    public function test_rename_table(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->rename('name');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The database driver does not support renaming tables.');

        $blueprint->toSql($this->getConnection(), $this->getGrammar());
    }

    public function test_rename_index(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->renameIndex('foo', 'bar');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The database driver does not support renaming indexes.');

        $blueprint->toSql($this->getConnection(), $this->getGrammar());
    }

    public function test_rename_column(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->renameColumn('foo', 'bar');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The database driver does not support renaming columns/fields.');

        $blueprint->toSql($this->getConnection(), $this->getGrammar());
    }

    public function test_adding_primary_key(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->create();
        $blueprint->string('foo')->primary();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The database driver does not support alternative primary keys.');

        $blueprint->toSql($this->getConnection(), $this->getGrammar());
    }

    public function test_adding_foreign_key(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->string('foo')->primary();
        $blueprint->string('order_id');
        $blueprint->foreign('order_id')->references('id')->on('orders');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The database driver does not support foreign fields.');

        $blueprint->toSql($this->getConnection(), $this->getGrammar());
    }

    public function test_adding_index(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->index(['foo', 'bar'], 'baz');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame(['DEFINE INDEX "baz" ON TABLE "users" FIELDS "foo", "bar"'], $statements);
    }

    public function test_adding_index_unique(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->unique('foo', 'bar');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame(['DEFINE INDEX "bar" ON TABLE "users" FIELDS "foo" UNIQUE'], $statements);
    }

    public function test_adding_incrementing(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->integer('number')->autoIncrement();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The database driver does not support incrementing fields.');

        $blueprint->toSql($this->getConnection(), $this->getGrammar());
    }

    public function test_adding_string(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->string('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame(['DEFINE FIELD "foo" ON "users" TYPE string'], $statements);

        $blueprint = new Blueprint('users');
        $blueprint->string('foo', 100);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame(['DEFINE FIELD "foo" ON "users" TYPE string'], $statements);

        $blueprint = new Blueprint('users');
        $blueprint->string('foo', 100)->nullable()->default('bar');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame(['DEFINE FIELD "foo" ON "users" TYPE string VALUE "bar"'], $statements);
    }

    public function test_adding_text(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->text('foo');
        $blueprint->tinyText('bar');
        $blueprint->mediumText('baz');
        $blueprint->longText('quz');

        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame([
            'DEFINE FIELD "foo" ON "users" TYPE string',
            'DEFINE FIELD "bar" ON "users" TYPE string',
            'DEFINE FIELD "baz" ON "users" TYPE string',
            'DEFINE FIELD "quz" ON "users" TYPE string',
        ], $statements);
    }

    public function test_adding_integers()
    {
        $blueprint = new Blueprint('users');
        $blueprint->integer('foo');
        $blueprint->tinyInteger('bar');
        $blueprint->smallInteger('baz');
        $blueprint->mediumInteger('quz');
        $blueprint->bigInteger('qux');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame([
            'DEFINE FIELD "foo" ON "users" TYPE int',
            'DEFINE FIELD "bar" ON "users" TYPE int',
            'DEFINE FIELD "baz" ON "users" TYPE int',
            'DEFINE FIELD "quz" ON "users" TYPE int',
            'DEFINE FIELD "qux" ON "users" TYPE int',
        ], $statements);
    }

    public function test_adding_unsigned_integers()
    {
        $blueprint = new Blueprint('users');
        $blueprint->unsignedInteger('foo');
        $blueprint->unsignedTinyInteger('bar');
        $blueprint->unsignedSmallInteger('baz');
        $blueprint->unsignedMediumInteger('quz');
        $blueprint->unsignedBigInteger('qux');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame([
            'DEFINE FIELD "foo" ON "users" TYPE int',
            'DEFINE FIELD "bar" ON "users" TYPE int',
            'DEFINE FIELD "baz" ON "users" TYPE int',
            'DEFINE FIELD "quz" ON "users" TYPE int',
            'DEFINE FIELD "qux" ON "users" TYPE int',
        ], $statements);
    }

    public function test_adding_float(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->float('foo');
        $blueprint->unsignedFloat('bar');
        $blueprint->double('baz');
        $blueprint->unsignedDouble('quz');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame([
            'DEFINE FIELD "foo" ON "users" TYPE float',
            'DEFINE FIELD "bar" ON "users" TYPE float',
            'DEFINE FIELD "baz" ON "users" TYPE float',
            'DEFINE FIELD "quz" ON "users" TYPE float',
        ], $statements);
    }

    public function test_adding_decimal(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->decimal('foo');
        $blueprint->unsignedDecimal('bar');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame([
            'DEFINE FIELD "foo" ON "users" TYPE decimal',
            'DEFINE FIELD "bar" ON "users" TYPE decimal',
        ], $statements);
    }

    public function test_adding_boolean(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->boolean('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame(['DEFINE FIELD "foo" ON "users" TYPE bool'], $statements);
    }

    public function test_adding_json(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->json('foo');
        $blueprint->jsonb('bar');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame([
            'DEFINE FIELD "foo" ON "users" TYPE string',
            'DEFINE FIELD "bar" ON "users" TYPE string'
        ], $statements);
    }

    public function test_adding_dates(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->date('foo');
        $blueprint->dateTime('bar');
        $blueprint->dateTimeTz('baz');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame([
            'DEFINE FIELD "foo" ON "users" TYPE datetime',
            'DEFINE FIELD "bar" ON "users" TYPE datetime',
            'DEFINE FIELD "baz" ON "users" TYPE datetime',
        ], $statements);
    }

    public function test_adding_year(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->year('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame(['DEFINE FIELD "foo" ON "users" TYPE int'], $statements);
    }

    public function test_adding_times(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->time('foo');
        $blueprint->timeTz('bar');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame([
            'DEFINE FIELD "foo" ON "users" TYPE int',
            'DEFINE FIELD "bar" ON "users" TYPE int',
        ], $statements);
    }

    public function test_adding_timestamps(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->timestamp('foo');
        $blueprint->timestampTz('bar');
        $blueprint->timestamp('baz')->useCurrent();
        $blueprint->timestampTz('quz')->default('2020-01-01 17:30:32');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame([
            'DEFINE FIELD "foo" ON "users" TYPE datetime',
            'DEFINE FIELD "bar" ON "users" TYPE datetime',
            'DEFINE FIELD "baz" ON "users" TYPE datetime VALUE time::now()',
            'DEFINE FIELD "quz" ON "users" TYPE datetime VALUE "2020-01-01 17:30:32"',
        ], $statements);
    }

    public function test_adding_model_timestamps(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->timestamps();
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame([
            'DEFINE FIELD "created_at" ON "users" TYPE datetime',
            'DEFINE FIELD "updated_at" ON "users" TYPE datetime',
        ], $statements);
    }

    public function test_adding_model_timestamps_with_tz(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->timestampsTz();
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame([
            'DEFINE FIELD "created_at" ON "users" TYPE datetime',
            'DEFINE FIELD "updated_at" ON "users" TYPE datetime',
        ], $statements);
    }

    public function test_adding_remember_token(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->rememberToken();
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame(['DEFINE FIELD "remember_token" ON "users" TYPE string'], $statements);
    }

    public function test_adding_binary(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->binary('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame(['DEFINE FIELD "foo" ON "users" TYPE string'], $statements);
    }

    public function test_adding_uuid(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->uuid('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame(['DEFINE FIELD "foo" ON "users" TYPE string'], $statements);
    }

    public function test_adding_ip_address(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->ipAddress('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame(['DEFINE FIELD "foo" ON "users" TYPE string'], $statements);
    }

    public function test_adding_mac_address(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->macAddress('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame(['DEFINE FIELD "foo" ON "users" TYPE string'], $statements);
    }

    public function test_adding_geometries(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->geometry('foo');
        $blueprint->point('bar');
        $blueprint->lineString('baz');
        $blueprint->polygon('quz');
        $blueprint->geometryCollection('qux');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame([
            'DEFINE FIELD "foo" ON "users" TYPE geometry(feature)',
            'DEFINE FIELD "bar" ON "users" TYPE geometry(point)',
            'DEFINE FIELD "baz" ON "users" TYPE geometry(line)',
            'DEFINE FIELD "quz" ON "users" TYPE geometry(polygon)',
            'DEFINE FIELD "qux" ON "users" TYPE geometry(collection)',
        ], $statements);
    }

    public function test_adding_multi_geometries(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->multiPoint('foo');
        $blueprint->multiLineString('bar');
        $blueprint->multiPolygon('baz');
        $blueprint->multiPolygonZ('quz');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame([
            'DEFINE FIELD "foo" ON "users" TYPE geometry(multipoint)',
            'DEFINE FIELD "bar" ON "users" TYPE geometry(multiline)',
            'DEFINE FIELD "baz" ON "users" TYPE geometry(multipolygon)',
            'DEFINE FIELD "quz" ON "users" TYPE geometry(multipolygon)',
        ], $statements);
    }

    public function test_add_surreal_any(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->any('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame(['DEFINE FIELD "foo" ON "users" TYPE any'], $statements);
    }

    public function test_add_surreal_array(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->array('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame(['DEFINE FIELD "foo" ON "users" TYPE array'], $statements);
    }

    public function test_add_surreal_duration(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->duration('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame(['DEFINE FIELD "foo" ON "users" TYPE duration'], $statements);
    }

    public function test_add_surreal_number(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->number('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame(['DEFINE FIELD "foo" ON "users" TYPE number'], $statements);
    }

    public function test_add_surreal_object(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->object('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame(['DEFINE FIELD "foo" ON "users" TYPE object'], $statements);
    }

    public function test_add_surreal_record(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->record('foo', ['bar', 'baz']);
        $blueprint->record('quz', 'qux');
        $blueprint->record('fred', 'thud')->default('corge');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame([
            'DEFINE FIELD "foo" ON "users" TYPE record(bar,baz)',
            'DEFINE FIELD "quz" ON "users" TYPE record(qux)',
            'DEFINE FIELD "fred" ON "users" TYPE record(thud) VALUE "corge"',
        ], $statements);
    }

    public function test_add_surreal_geo_json(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->geoJson('foo');
        $blueprint->geoJson('bar', [
            'feature',
            'point',
            'line',
            'polygon',
            'multipoint',
            'multiline',
            'multipolygon',
            'collection',
        ]);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertSame([
            'DEFINE FIELD "foo" ON "users" TYPE geometry(feature)',
            'DEFINE FIELD "bar" ON "users" TYPE geometry(feature,point,line,polygon,multipoint,multiline,multipolygon,collection)',
        ], $statements);
    }

    public function test_throws_when_add_surreal_geo_json_invalid_type(): void
    {
        $blueprint = new Blueprint('users');
        $blueprint->geoJson('orders', ['invalid', 'also invalid']);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid GeoJSON types for orders: invalid, also invalid.');

        $blueprint->toSql($this->getConnection(), $this->getGrammar());
    }
}
