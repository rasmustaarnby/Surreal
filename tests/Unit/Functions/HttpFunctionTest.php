<?php

namespace Tests\Unit\Functions;

use Laragear\Surreal\Query\Func;
use PHPUnit\Framework\TestCase;

class HttpFunctionTest extends TestCase
{
    public function test_put(): void
    {
        static::assertSame(
            'http::put(foo, {"bar":"baz"})',
            Func::http()->put('foo', ['bar' => 'baz'])->toSql()
        );

        static::assertSame(
            'http::put(foo, {"bar":"baz"}, {"quz":"qux"})',
            Func::http()->put('foo', ['bar' => 'baz'], ['quz' => 'qux'])->toSql()
        );
    }

    public function test_get(): void
    {
        static::assertSame(
            'http::get(foo)',
            Func::http()->get('foo')->toSql()
        );

        static::assertSame(
            'http::get(foo, {"quz":"qux"})',
            Func::http()->get('foo', ['quz' => 'qux'])->toSql()
        );
    }

    public function test_patch(): void
    {
        static::assertSame(
            'http::patch(foo, {"bar":"baz"})',
            Func::http()->patch('foo', ['bar' => 'baz'])->toSql()
        );

        static::assertSame(
            'http::patch(foo, {"bar":"baz"}, {"quz":"qux"})',
            Func::http()->patch('foo', ['bar' => 'baz'], ['quz' => 'qux'])->toSql()
        );
    }

    public function test_delete(): void
    {
        static::assertSame(
            'http::delete(foo)',
            Func::http()->delete('foo')->toSql()
        );

        static::assertSame(
            'http::delete(foo, {"quz":"qux"})',
            Func::http()->delete('foo', ['quz' => 'qux'])->toSql()
        );
    }

    public function test_post(): void
    {
        static::assertSame(
            'http::post(foo, {"bar":"baz"})',
            Func::http()->post('foo', ['bar' => 'baz'])->toSql()
        );

        static::assertSame(
            'http::post(foo, {"bar":"baz"}, {"quz":"qux"})',
            Func::http()->post('foo', ['bar' => 'baz'], ['quz' => 'qux'])->toSql()
        );
    }

    public function test_head(): void
    {
        static::assertSame(
            'http::head(foo)',
            Func::http()->head('foo')->toSql()
        );

        static::assertSame(
            'http::head(foo, {"quz":"qux"})',
            Func::http()->head('foo', ['quz' => 'qux'])->toSql()
        );
    }
}
