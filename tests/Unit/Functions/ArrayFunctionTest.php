<?php

namespace Tests\Unit\Functions;

use Laragear\Surreal\Query\Func;
use PHPUnit\Framework\TestCase;

class ArrayFunctionTest extends TestCase
{
    public function test_combine(): void
    {
        static::assertSame(
            'array::combine(foo, bar)',
            Func::array()->combine('foo', 'bar')->toSql()
        );
    }

    public function test_concat(): void
    {
        static::assertSame(
            'array::concat(foo, bar)',
            Func::array()->concat('foo', 'bar')->toSql()
        );
    }

    public function test_difference(): void
    {
        static::assertSame(
            'array::difference(foo, bar)',
            Func::array()->difference('foo', 'bar')->toSql()
        );

        static::assertSame(
            'array::difference(foo, bar)',
            Func::array()->diff('foo', 'bar')->toSql()
        );
    }

    public function test_distinct(): void
    {
        static::assertSame(
            'array::distinct(foo)',
            Func::array()->distinct('foo')->toSql()
        );
    }

    public function test_intersect(): void
    {
        static::assertSame(
            'array::intersect(foo, bar)',
            Func::array()->intersect('foo', 'bar')->toSql()
        );
    }

    public function test_len(): void
    {
        static::assertSame(
            'array::len(foo)',
            Func::array()->len('foo')->toSql()
        );

        static::assertSame(
            'array::len(foo)',
            Func::array()->length('foo')->toSql()
        );
    }

    public function test_sort(): void
    {
        static::assertSame(
            'array::sort::asc(foo)',
            Func::array()->sort('foo')->toSql()
        );

        static::assertSame(
            'array::sort::asc(foo)',
            Func::array()->sortAsc('foo')->toSql()
        );

        static::assertSame(
            'array::sort::desc(foo)',
            Func::array()->sort('foo', false)->toSql()
        );

        static::assertSame(
            'array::sort::desc(foo)',
            Func::array()->sortDesc('foo')->toSql()
        );
    }

    public function test_union(): void
    {
        static::assertSame(
            'array::union(foo, bar)',
            Func::array()->union('foo', 'bar')->toSql()
        );
    }
}
