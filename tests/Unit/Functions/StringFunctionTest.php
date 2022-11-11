<?php

namespace Functions;

use Laragear\Surreal\Query\Func;
use Orchestra\Testbench\TestCase;

class StringFunctionTest extends TestCase
{
    public function test_alias(): void
    {
        static::assertEquals(Func::string(), Func::str());
    }

    public function test_concat(): void
    {
        static::assertSame(
            'string::concat()',
            Func::string()->concat()->toSql()
        );

        static::assertSame(
            'string::concat(foo, bar)',
            Func::string()->concat('foo', 'bar')->toSql()
        );

        static::assertSame(
            'string::concat()',
            Func::string()->concatenate()->toSql()
        );

        static::assertSame(
            'string::concat(foo, bar)',
            Func::string()->concatenate('foo', 'bar')->toSql()
        );
    }

    public function test_endsWith(): void
    {
        static::assertSame(
            'string::endsWith(foo, bar)',
            Func::string()->endsWith('foo', 'bar')->toSql()
        );
    }

    public function test_join(): void
    {
        static::assertSame(
            'string::join(foo, bar, baz)',
            Func::string()->join('foo', 'bar', 'baz')->toSql()
        );
    }

    public function test_length(): void
    {
        static::assertSame(
            'string::length(foo)',
            Func::string()->length('foo')->toSql()
        );

        static::assertSame(
            'string::length(foo)',
            Func::string()->len('foo')->toSql()
        );
    }

    public function test_lowercase(): void
    {
        static::assertSame(
            'string::lowercase(foo)',
            Func::string()->lowercase('foo')->toSql()
        );

        static::assertSame(
            'string::lowercase(foo)',
            Func::string()->lc('foo')->toSql()
        );
    }

    public function test_repeat(): void
    {
        static::assertSame(
            'string::repeat(foo, 10)',
            Func::string()->repeat('foo', 10)->toSql()
        );
    }

    public function test_replace(): void
    {
        static::assertSame(
            'string::replace(foo, bar, baz)',
            Func::string()->replace('foo', 'bar', 'baz')->toSql()
        );
    }

    public function test_reverse(): void
    {
        static::assertSame(
            'string::reverse(foo)',
            Func::string()->reverse('foo')->toSql()
        );
    }

    public function test_slice(): void
    {
        static::assertSame(
            'string::slice(foo, 1, 2)',
            Func::string()->slice('foo', 1, 2)->toSql()
        );
    }

    public function test_slug(): void
    {
        static::assertSame(
            'string::slug(foo)',
            Func::string()->slug('foo')->toSql()
        );
    }

    public function test_split(): void
    {
        static::assertSame(
            'string::split(foo, bar)',
            Func::string()->split('foo', 'bar')->toSql()
        );
    }

    public function test_startsWith(): void
    {
        static::assertSame(
            'string::startsWith(foo, bar)',
            Func::string()->startsWith('foo', 'bar')->toSql()
        );
    }

    public function test_trim(): void
    {
        static::assertSame(
            'string::trim(foo)',
            Func::string()->trim('foo')->toSql()
        );
    }

    public function test_uppercase(): void
    {
        static::assertSame(
            'string::uppercase(foo)',
            Func::string()->uppercase('foo')->toSql()
        );

        static::assertSame(
            'string::uppercase(foo)',
            Func::string()->uc('foo')->toSql()
        );
    }

    public function test_words(): void
    {
        static::assertSame(
            'string::words(foo)',
            Func::string()->words('foo')->toSql()
        );
    }
}
