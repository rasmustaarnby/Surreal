<?php

namespace Tests\Unit\Functions;

use Laragear\Surreal\Query\Func;
use PHPUnit\Framework\TestCase;

class MathFunctionTest extends TestCase
{
    public function test_abs(): void
    {
        static::assertSame(
            'math::abs(foo)',
            Func::math()->abs('foo')->toSql()
        );

        static::assertSame(
            'math::abs(foo)',
            Func::math()->absolute('foo')->toSql()
        );
    }

    public function test_ceil(): void
    {
        static::assertSame(
            'math::ceil(foo)',
            Func::math()->ceil('foo')->toSql()
        );
    }

    public function test_fixed(): void
    {
        static::assertSame(
            'math::fixed(foo, 1)',
            Func::math()->fixed('foo', 1)->toSql()
        );
    }

    public function test_floor(): void
    {
        static::assertSame(
            'math::floor(foo)',
            Func::math()->floor('foo')->toSql()
        );
    }

    public function test_max(): void
    {
        static::assertSame(
            'math::max(foo)',
            Func::math()->max('foo')->toSql()
        );
    }

    public function test_mean(): void
    {
        static::assertSame(
            'math::mean(foo)',
            Func::math()->mean('foo')->toSql()
        );
    }

    public function test_median(): void
    {
        static::assertSame(
            'math::median(foo)',
            Func::math()->median('foo')->toSql()
        );
    }

    public function test_min(): void
    {
        static::assertSame(
            'math::min(foo)',
            Func::math()->min('foo')->toSql()
        );
    }

    public function test_product(): void
    {
        static::assertSame(
            'math::product(foo)',
            Func::math()->product('foo')->toSql()
        );
    }

    public function test_round(): void
    {
        static::assertSame(
            'math::round(foo)',
            Func::math()->round('foo')->toSql()
        );
    }

    public function test_sqrt(): void
    {
        static::assertSame(
            'math::sqrt(foo)',
            Func::math()->sqrt('foo')->toSql()
        );

        static::assertSame(
            'math::sqrt(foo)',
            Func::math()->squareRoot('foo')->toSql()
        );
    }

    public function test_sum(): void
    {
        static::assertSame(
            'math::sum(foo)',
            Func::math()->sum('foo')->toSql()
        );
    }
}
