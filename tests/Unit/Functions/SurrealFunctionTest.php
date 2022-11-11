<?php

namespace Functions;

use Laragear\Surreal\Functions\SurrealFunction;
use PHPUnit\Framework\TestCase;

class SurrealFunctionTest extends TestCase
{
    public function test_expression_to_sql(): void
    {
        $function = new SurrealFunction('foo $?', ['bar', 'baz']);

        static::assertSame('foo bar', (string) $function);
        static::assertSame('foo bar', $function->toSql());

        $function->as('quz');

        static::assertSame('foo bar AS quz', (string) $function);
        static::assertSame('foo bar AS quz', $function->toSql());
    }

    public function test_expression_to_json(): void
    {
        $function = new SurrealFunction('foo $?', ['bar', 'baz']);

        static::assertSame('"foo bar"', $function->toJson());

        $function->as('quz');

        static::assertSame('"foo bar AS quz"', $function->toJson());
    }
}
