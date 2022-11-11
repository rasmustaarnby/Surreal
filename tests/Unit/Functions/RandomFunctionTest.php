<?php

namespace Tests\Unit\Functions;

use Laragear\Surreal\Query\Func;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class RandomFunctionTest extends TestCase
{
    public function test_alias(): void
    {
        static::assertEquals(Func::rand(), Func::random());
    }

    public function test_bool(): void
    {
        static::assertSame(
            'rand::bool()',
            Func::rand()->bool()->toSql()
        );
    }

    public function test_enum(): void
    {
        static::assertSame(
            'rand::enum()',
            Func::rand()->enum()->toSql()
        );

        static::assertSame(
            'rand::enum(foo, bar)',
            Func::rand()->enum('foo', 'bar')->toSql()
        );
    }

    public function test_float(): void
    {
        static::assertSame(
            'rand::float()',
            Func::rand()->float()->toSql()
        );

        static::assertSame(
            'rand::float(1, 2)',
            Func::rand()->float(1, 2)->toSql()
        );
    }

    public function test_throws_float_if_only_one_argument_present(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The float function parameters requires both or none.');

        Func::rand()->float(1);
    }

    public function test_guid(): void
    {
        static::assertSame(
            'rand::guid()',
            Func::rand()->guid()->toSql()
        );

        static::assertSame(
            'rand::guid(10)',
            Func::rand()->guid(10)->toSql()
        );
    }

    public function test_int(): void
    {
        static::assertSame(
            'rand::int()',
            Func::rand()->int()->toSql()
        );

        static::assertSame(
            'rand::int(1.2, 3.4)',
            Func::rand()->int(1.2, 3.4)->toSql()
        );
    }

    public function test_int_if_only_one_argument_present(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The int function parameters requires both or none.');

        Func::rand()->int(1);
    }

    public function test_string(): void
    {
        static::assertSame(
            'rand::string()',
            Func::rand()->string()->toSql()
        );

        static::assertSame(
            'rand::string(1)',
            Func::rand()->string(1)->toSql()
        );

        static::assertSame(
            'rand::string(1, 1)',
            Func::rand()->string(1, 1)->toSql()
        );
    }

    public function test_time(): void
    {
        static::assertSame(
            'rand::time()',
            Func::rand()->time()->toSql()
        );

        static::assertSame(
            'rand::time(1, 2)',
            Func::rand()->time(1, 2)->toSql()
        );
    }

    public function test_throws_time_if_only_one_argument_present(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The time function parameters requires both or none.');

        Func::rand()->time(1);
    }

    public function test_uuid(): void
    {
        static::assertSame(
            'rand::uuid()',
            Func::rand()->uuid()->toSql()
        );
    }
}
