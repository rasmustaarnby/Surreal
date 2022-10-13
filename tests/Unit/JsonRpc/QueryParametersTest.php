<?php

namespace Tests\Unit\JsonRpc;

use Laragear\Surreal\JsonRpc\QueryParameters;
use PHPUnit\Framework\TestCase;
use function json_encode;

class QueryParametersTest extends TestCase
{
    public function test_serializes_forcefully_into_json_object(): void
    {
        $query = new QueryParameters(['foo', 'bar']);

        static::assertSame('{"0":"foo","1":"bar"}', (string) $query);
        static::assertSame('{"0":"foo","1":"bar"}', $query->toJson());
        static::assertSame('{"0":"foo","1":"bar"}', json_encode($query));
    }
}
