<?php

namespace Functions;

use Generator;
use Laragear\Surreal\Functions\TimeFunction;
use Laragear\Surreal\Query\Func;
use PHPUnit\Framework\TestCase;

class TimeFunctionTest extends TestCase
{
    public function test_alias(): void
    {
        static::assertEquals(Func::time(), Func::date());
    }

    public function test_day(): void
    {
        static::assertSame(
            'time::day(foo)',
            Func::time()->day('foo')->toSql()
        );
    }

    public function test_floor(): void
    {
        static::assertSame(
            "time::floor(foo, bar)",
            Func::time()->floor('foo', 'bar')->toSql()
        );
    }

    /**
     * @dataProvider provideGroupValidValues
     */
    public function test_group($group): void
    {
        static::assertSame(
            "time::group(foo, $group)",
            Func::time()->group('foo', $group)->toSql()
        );
    }

    public function provideGroupValidValues(): Generator
    {
        foreach (TimeFunction::GROUP_VALUES as $value) {
            yield $value => [$value];
        }
    }

    public function test_hour(): void
    {
        static::assertSame(
            'time::hour(foo)',
            Func::time()->hour('foo')->toSql()
        );
    }

    public function test_mins(): void
    {
        static::assertSame(
            'time::mins(foo)',
            Func::time()->mins('foo')->toSql()
        );

        static::assertSame(
            'time::mins(foo)',
            Func::time()->minutes('foo')->toSql()
        );
    }

    public function test_month(): void
    {
        static::assertSame(
            'time::month(foo)',
            Func::time()->month('foo')->toSql()
        );
    }

    public function test_nano(): void
    {
        static::assertSame(
            'time::nano(foo)',
            Func::time()->nano('foo')->toSql()
        );
    }

    public function test_now(): void
    {
        static::assertSame(
            'time::now()',
            Func::time()->now()->toSql()
        );
    }

    public function test_round(): void
    {
        static::assertSame(
            'time::round(foo, bar)',
            Func::time()->round('foo', 'bar')->toSql()
        );
    }

    public function test_secs(): void
    {
        static::assertSame(
            'time::secs(foo)',
            Func::time()->secs('foo')->toSql()
        );

        static::assertSame(
            'time::secs(foo)',
            Func::time()->seconds('foo')->toSql()
        );
    }

    public function test_unix(): void
    {
        static::assertSame(
            'time::unix(foo)',
            Func::time()->unix('foo')->toSql()
        );
    }

    public function test_wday(): void
    {
        static::assertSame(
            'time::wday(foo)',
            Func::time()->wday('foo')->toSql()
        );

        static::assertSame(
            'time::wday(foo)',
            Func::time()->weekDay('foo')->toSql()
        );
    }

    public function test_week(): void
    {
        static::assertSame(
            'time::week(foo)',
            Func::time()->week('foo')->toSql()
        );
    }

    public function test_yday(): void
    {
        static::assertSame(
            'time::yday(foo)',
            Func::time()->yday('foo')->toSql()
        );

        static::assertSame(
            'time::yday(foo)',
            Func::time()->yearDay('foo')->toSql()
        );
    }

    public function test_year(): void
    {
        static::assertSame(
            'time::year(foo)',
            Func::time()->year('foo')->toSql()
        );
    }
}
