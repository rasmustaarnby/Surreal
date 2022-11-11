<?php

namespace Functions;

use Laragear\Surreal\Query\Func;
use PHPUnit\Framework\TestCase;

class ValidationFunctionTest extends TestCase
{
    public function test_alias(): void
    {
        static::assertEquals(Func::is(), Func::validate());
    }

    public function test_alphanum(): void
    {
        static::assertSame(
            'is::alphanum(foo)',
            Func::is()->alphanum('foo')->toSql()
        );
    }

    public function test_alpha(): void
    {
        static::assertSame(
            'is::alpha(foo)',
            Func::is()->alpha('foo')->toSql()
        );
    }

    public function test_ascii(): void
    {
        static::assertSame(
            'is::ascii(foo)',
            Func::is()->ascii('foo')->toSql()
        );
    }

    public function test_domain(): void
    {
        static::assertSame(
            'is::domain(foo)',
            Func::is()->domain('foo')->toSql()
        );
    }

    public function test_email(): void
    {
        static::assertSame(
            'is::email(foo)',
            Func::is()->email('foo')->toSql()
        );
    }

    public function test_hexadecimal(): void
    {
        static::assertSame(
            'is::hexadecimal(foo)',
            Func::is()->hexadecimal('foo')->toSql()
        );
    }

    public function test_latitude(): void
    {
        static::assertSame(
            'is::latitude(foo)',
            Func::is()->latitude('foo')->toSql()
        );
    }

    public function test_longitude(): void
    {
        static::assertSame(
            'is::longitude(foo)',
            Func::is()->longitude('foo')->toSql()
        );
    }

    public function test_numeric(): void
    {
        static::assertSame(
            'is::numeric(foo)',
            Func::is()->numeric('foo')->toSql()
        );
    }

    public function test_semver(): void
    {
        static::assertSame(
            'is::semver(foo)',
            Func::is()->semver('foo')->toSql()
        );
    }

    public function test_uuid(): void
    {
        static::assertSame(
            'is::uuid(foo)',
            Func::is()->uuid('foo')->toSql()
        );
    }
}
