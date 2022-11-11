<?php

namespace Tests\Feature\Query;

use Laragear\Surreal\Functions\SurrealFunction;
use Tests\AssertsMockConnection;
use Tests\TestCase;

class BuilderCreateTest extends TestCase
{
    use AssertsMockConnection;

    public function test_creates_default_record(): void
    {
        $this->expectsMessage('CREATE `user`');

        $this->surreal->table('user')->create();
    }

    public function test_creates_record_with_values(): void
    {
        $this->expectsMessage('CREATE `user` CONTENT { "foo" : $?, "baz" : $? }', ['foo' => 'bar', 'baz' => 'quz']);

        $this->surreal->table('user')->create(['foo' => 'bar', 'baz' => 'quz']);
    }

    public function test_creates_record_id_without_values(): void
    {
        $this->expectsMessage('CREATE "user:bar"');

        $this->surreal->id('user:bar')->create();
    }

    public function test_creates_record_id_with_values(): void
    {
        $this->expectsMessage('CREATE "user:bar" CONTENT { "foo" : $? }', ['foo' => 'bar']);

        $this->surreal->id('user:bar')->create(['foo' => 'bar']);
    }

    public function test_adds_function_bindings(): void
    {
        $this->expectsMessage('CREATE "user:bar" CONTENT { "column" : foo::bar($?) }', ['baz']);

        $this->surreal->id('user:bar')->create([
            'column' => SurrealFunction::make('foo::bar($?)', ['baz'])
        ]);
    }

    public function test_adds_nested_function_as_raw_expression(): void
    {
        $function = SurrealFunction::make('bar::quz($?)', ['qux']);

        $this->expectsMessage(
            'CREATE "user:bar" CONTENT { "foo" : foo::bar($?, $?), "bar" : $?, "quz" : $?, "fred" : fred::thud($?) }',
            [
                'bar' => 'baz',
                'quz' => [$function],
                '0' => 'baz',
                '1' => 'quz',
                '2' => 'thud',
            ]
        );

        $this->surreal->id('user:bar')->create([
            'foo' => SurrealFunction::make('foo::bar($?, $?)', ['baz', 'quz']),
            'bar' => 'baz',
            'quz' => [$function],
            'fred' => SurrealFunction::make('fred::thud($?)', ['thud'])
        ]);
    }
}
