<?php

namespace Tests\Unit\Functions;

use Laragear\Surreal\Query\Func;
use Laragear\Surreal\Types\Geometry;
use PHPUnit\Framework\TestCase;

class GeoFunctionTest extends TestCase
{
    protected function polygon()
    {
        return Geometry::polygon([1, 2]);
    }

    public function test_area(): void
    {
        static::assertSame(
            'geo::area(foo)',
            Func::geo()->area('foo')->toSql()
        );

        static::assertSame(
            'geo::area({"type":"Polygon","coordinates":[[1,2]]})',
            Func::geo()->area($this->polygon())->toSql()
        );
    }

    public function test_bearing(): void
    {
        static::assertSame(
            'geo::bearing(foo, bar)',
            Func::geo()->bearing('foo', 'bar')->toSql()
        );
    }

    public function test_centroid(): void
    {
        static::assertSame(
            'geo::centroid(foo)',
            Func::geo()->centroid('foo')->toSql()
        );
        static::assertSame(
            'geo::centroid({"type":"Polygon","coordinates":[[1,2]]})',
            Func::geo()->centroid($this->polygon())->toSql()
        );
    }

    public function test_distance(): void
    {
        static::assertSame(
            'geo::distance(foo, bar)',
            Func::geo()->distance('foo', 'bar')->toSql()
        );
    }

    public function test_hash_decode(): void
    {
        static::assertSame(
            'geo::hash::decode(foo)',
            Func::geo()->hashDecode('foo')->toSql()
        );
    }

    public function test_hash_encode(): void
    {
        static::assertSame(
            'geo::hash::encode(foo)',
            Func::geo()->hashEncode('foo')->toSql()
        );

        static::assertSame(
            'geo::hash::encode(foo, 10)',
            Func::geo()->hashEncode('foo', 10)->toSql()
        );
    }
}
