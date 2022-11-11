<?php

namespace Tests\Feature\Query;

use Laragear\Surreal\Functions\SurrealFunction;
use Tests\AssertsMockConnection;
use Tests\TestCase;

class BuilderRelateTest extends TestCase
{
    use AssertsMockConnection;

    public function test_relates_to_record(): void
    {
        $this->expectsMessage('RELATE user:1->knows->user:2');
        $this->expectsMessage('RELATE user:3->knows->user:4');

        $this->surreal->id('user:1')->relateTo('user:2')->through('knows');
        $this->surreal->id('user:3')->relateTo('user:4')->knows();
    }

    public function test_relates_with_edge_values(): void
    {
        $this->expectsMessage('RELATE user:1->knows->user:2 CONTENT { "foo" : $? }', ['foo' => 'bar']);
        $this->expectsMessage('RELATE user:3->knows->user:4 CONTENT { "foo" : $? }', ['foo' => 'bar']);

        $this->surreal->id('user:1')->relateTo('user:2')->through('knows', ['foo' => 'bar']);
        $this->surreal->id('user:3')->relateTo('user:4')->knows(['foo' => 'bar']);
    }

    public function test_relates_with_flags(): void
    {
        $this->expectsMessage('RELATE user:1->knows->user:2 CONTENT { "foo" : $? } RETURN NONE TIMEOUT 5s PARALLEL', ['foo' => 'bar']);
        $this->expectsMessage('RELATE user:3->knows->user:4 CONTENT { "foo" : $? } RETURN NONE TIMEOUT 5s PARALLEL', ['foo' => 'bar']);

        $this->surreal->id('user:1')->parallel()->timeout(5)->returnNone()->relateTo('user:2')->through('knows', ['foo' => 'bar']);
        $this->surreal->id('user:3')->parallel()->timeout(5)->returnNone()->relateTo('user:4')->knows(['foo' => 'bar']);
    }

    public function test_relates_with_function(): void
    {
        $this->expectsMessage('RELATE user:1->knows->user:2 CONTENT { "foo" : bar::quz($?) }', ['qux']);

        $this->surreal->id('user:1')->relateTo('user:2')->through('knows', [
            'foo' => SurrealFunction::make('bar::quz($?)', ['qux'])
        ]);
    }
}
