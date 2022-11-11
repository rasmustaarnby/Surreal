<?php

namespace Functions;

use Laragear\Surreal\Query\Func;
use Orchestra\Testbench\TestCase;

class TypeFunctionTest extends TestCase
{

    public function test_bool(): void
    {
        static::assertSame(
            'type::bool(foo)',
            Func::type()->bool('foo')->toSql()
        );
    }

    public function test_datetime(): void
    {
        static::assertSame(
            'type::datetime(foo)',
            Func::type()->datetime('foo')->toSql()
        );
    }

    public function test_decimal(): void
    {
        static::assertSame(
            'type::decimal(foo)',
            Func::type()->decimal('foo')->toSql()
        );
    }

    public function test_duration(): void
    {
        static::assertSame(
            'type::duration(foo)',
            Func::type()->duration('foo')->toSql()
        );
    }

    public function test_float(): void
    {
        static::assertSame(
            'type::float(foo)',
            Func::type()->float('foo')->toSql()
        );
    }

    public function test_int(): void
    {
        static::assertSame(
            'type::int(foo)',
            Func::type()->int('foo')->toSql()
        );

        static::assertSame(
            'type::int(foo)',
            Func::type()->integer('foo')->toSql()
        );
    }

    public function test_number(): void
    {
        static::assertSame(
            'type::number(foo)',
            Func::type()->number('foo')->toSql()
        );
    }

    public function test_point(): void
    {
        static::assertSame(
            'type::point(foo)',
            Func::type()->point('foo')->toSql()
        );

        static::assertSame(
            'type::point(foo, bar)',
            Func::type()->point('foo', 'bar')->toSql()
        );
    }

    public function test_regex(): void
    {
        static::assertSame(
            'type::regex(foo)',
            Func::type()->regex('foo')->toSql()
        );
    }

    public function test_string(): void
    {
        static::assertSame(
            'type::string(foo)',
            Func::type()->string('foo')->toSql()
        );

        static::assertSame(
            'type::string(foo)',
            Func::type()->str('foo')->toSql()
        );
    }

    public function test_table(): void
    {
        static::assertSame(
            'type::table(foo)',
            Func::type()->table('foo')->toSql()
        );
    }

    public function test_thing(): void
    {
        static::assertSame(
            'type::thing(foo, bar)',
            Func::type()->thing('foo', 'bar')->toSql()
        );

        static::assertSame(
            'type::thing(foo, bar)',
            Func::type()->record('foo', 'bar')->toSql()
        );
    }
}
