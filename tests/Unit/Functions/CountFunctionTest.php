<?php

namespace Tests\Unit\Functions;

use Laragear\Surreal\Query\Func;
use PHPUnit\Framework\TestCase;

class CountFunctionTest extends TestCase
{
    public function test_count(): void
    {
        static::assertSame(
            'count(foo)',
            Func::count('foo')->toSql()
        );
    }
}
