<?php

namespace Functions;

use Laragear\Surreal\Query\Func;
use PHPUnit\Framework\TestCase;

class SessionFunctionTest extends TestCase
{
    public function test_db(): void
    {
        static::assertSame(
            'session::db()',
            Func::session()->db()->toSql()
        );

        static::assertSame(
            'session::db()',
            Func::session()->database()->toSql()
        );
    }

    public function test_id(): void
    {
        static::assertSame(
            'session::id()',
            Func::session()->id()->toSql()
        );
    }

    public function test_ip(): void
    {
        static::assertSame(
            'session::ip()',
            Func::session()->ip()->toSql()
        );
    }

    public function test_ns(): void
    {
        static::assertSame(
            'session::ns()',
            Func::session()->ns()->toSql()
        );

        static::assertSame(
            'session::ns()',
            Func::session()->namespace()->toSql()
        );
    }

    public function test_origin(): void
    {
        static::assertSame(
            'session::origin()',
            Func::session()->origin()->toSql()
        );
    }

    public function test_sc(): void
    {
        static::assertSame(
            'session::sc()',
            Func::session()->sc()->toSql()
        );

        static::assertSame(
            'session::sc()',
            Func::session()->scope()->toSql()
        );
    }
}
