<?php

namespace Tests\Unit\JsonRpc;

use Laragear\Surreal\JsonRpc\Statement;
use PHPUnit\Framework\TestCase;
use function json_encode;

class StatementTest extends TestCase
{
    public function test_serializes_replacing_variables(): void
    {
        $statement = new Statement('foo ?$ bar $? quz ??$$ $?', ['fred', 'thud', 'out of bounds']);

        static::assertSame('"foo ?$ bar $fred quz ??$$ $thud"', (string) $statement);
        static::assertSame('"foo ?$ bar $fred quz ??$$ $thud"', $statement->toJson());
        static::assertSame('"foo ?$ bar $fred quz ??$$ $thud"', json_encode($statement));
    }
}
