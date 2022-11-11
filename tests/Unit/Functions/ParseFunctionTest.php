<?php

namespace Tests\Unit\Functions;

use Laragear\Surreal\Query\Func;
use PHPUnit\Framework\TestCase;

class ParseFunctionTest extends TestCase
{
    public function test_email_domain(): void
    {
        static::assertSame(
            'parse::email::domain(foo)',
            Func::parse()->emailDomain('foo')->toSql()
        );
    }

    public function test_email_user(): void
    {
        static::assertSame(
            'parse::email::user(foo)',
            Func::parse()->emailUser('foo')->toSql()
        );
    }

    public function test_url_domain(): void
    {
        static::assertSame(
            'parse::url::domain(foo)',
            Func::parse()->urlDomain('foo')->toSql()
        );
    }

    public function test_url_fragment(): void
    {
        static::assertSame(
            'parse::url::fragment(foo)',
            Func::parse()->urlFragment('foo')->toSql()
        );
    }

    public function test_url_host(): void
    {
        static::assertSame(
            'parse::url::host(foo)',
            Func::parse()->urlHost('foo')->toSql()
        );
    }

    public function test_url_path(): void
    {
        static::assertSame(
            'parse::url::path(foo)',
            Func::parse()->urlPath('foo')->toSql()
        );
    }

    public function test_url_port(): void
    {
        static::assertSame(
            'parse::url::port(foo)',
            Func::parse()->urlPort('foo')->toSql()
        );
    }

    public function test_url_query(): void
    {
        static::assertSame(
            'parse::url::query(foo)',
            Func::parse()->urlQuery('foo')->toSql()
        );
    }
}
