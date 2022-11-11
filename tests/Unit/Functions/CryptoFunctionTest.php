<?php

namespace Tests\Unit\Functions;

use Laragear\Surreal\Query\Func;
use PHPUnit\Framework\TestCase;

class CryptoFunctionTest extends TestCase
{
    public function test_md5(): void
    {
        static::assertSame(
            'crypto::md5(foo)',
            Func::crypto()->md5('foo')->toSql()
        );
    }

    public function test_sha1(): void
    {
        static::assertSame(
            'crypto::sha1(foo)',
            Func::crypto()->sha1('foo')->toSql()
        );
    }

    public function test_sha256(): void
    {
        static::assertSame(
            'crypto::sha256(foo)',
            Func::crypto()->sha256('foo')->toSql()
        );
    }

    public function test_sha512(): void
    {
        static::assertSame(
            'crypto::sha512(foo)',
            Func::crypto()->sha512('foo')->toSql()
        );
    }

    public function test_argon2_compare(): void
    {
        static::assertSame(
            'crypto::argon2::compare(foo, bar)',
            Func::crypto()->argon2Compare('foo', 'bar')->toSql()
        );
    }

    public function test_argon2_generate(): void
    {
        static::assertSame(
            'crypto::argon2::generate(foo)',
            Func::crypto()->argon2Generate('foo')->toSql()
        );
    }

    public function test_pbkdf_compare(): void
    {
        static::assertSame(
            'crypto::pbkdf2::compare(foo, bar)',
            Func::crypto()->pbkdf2Compare('foo', 'bar')->toSql()
        );
    }

    public function test_pbkdf_generate(): void
    {
        static::assertSame(
            'crypto::pbkdf2::generate(foo)',
            Func::crypto()->pbkdf2Generate('foo')->toSql()
        );
    }

    public function test_scrypt_compare(): void
    {
        static::assertSame(
            'crypto::scrypt::compare(foo, bar)',
            Func::crypto()->scryptCompare('foo', 'bar')->toSql()
        );
    }

    public function test_scrypt_generate(): void
    {
        static::assertSame(
            'crypto::scrypt::generate(foo)',
            Func::crypto()->scryptGenerate('foo')->toSql()
        );
    }
}
