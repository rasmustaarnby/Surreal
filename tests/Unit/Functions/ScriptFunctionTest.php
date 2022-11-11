<?php

namespace Functions;

use Laragear\Surreal\Query\Func;
use PHPUnit\Framework\TestCase;

class ScriptFunctionTest extends TestCase
{
    public function test_alias(): void
    {
        static::assertEquals(Func::script('foo'), Func::js('foo'));
    }

    public function test_script(): void
    {
        static::assertSame(
            <<<SCRIPT
function () {
    return "foo" + "bar"
}
SCRIPT,
            Func::script('return "foo" + "bar"')->toSql()
        );
    }
}
