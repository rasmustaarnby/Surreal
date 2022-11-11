<?php

namespace Tests\Unit\Types;

use Illuminate\Support\Collection;
use Laragear\Surreal\Types\Geometry;
use Laragear\Surreal\Types\GeometryType;
use PHPUnit\Framework\TestCase;

class GeometryTest extends TestCase
{
    public function test_creates_point(): void
    {
        $geometry = Geometry::point(1.2, 3.4);

        static::assertSame(GeometryType::Point, $geometry->type);
        static::assertSame('{"type":"Point","coordinates":[1.2,3.4]}', $geometry->toString());
    }

    public function test_creates_line(): void
    {
        $geometry = Geometry::line([1.2, 3.4], [5.6, 7.8]);

        static::assertSame(GeometryType::Line, $geometry->type);
        static::assertSame('{"type":"LineString","coordinates":[[1.2,3.4],[5.6,7.8]]}', $geometry->toString());
    }

    public function test_creates_polygon(): void
    {
        $geometry = Geometry::polygon([[1.2, 3.4], [5.6, 7.8]]);

        static::assertSame(GeometryType::Polygon, $geometry->type);
        static::assertSame('{"type":"Polygon","coordinates":[[[1.2,3.4],[5.6,7.8]]]}', $geometry->toString());
    }

    public function test_creates_multi_point(): void
    {
        $geometry = Geometry::multiPoint([[1.2, 3.4], [5.6, 7.8]]);

        static::assertSame(GeometryType::MultiPoint, $geometry->type);
        static::assertSame('{"type":"MultiPoint","coordinates":[[1.2,3.4],[5.6,7.8]]}', $geometry->toString());

        $geometry = Geometry::multiPoint(new Collection([[1.2, 3.4], [5.6, 7.8]]));

        static::assertSame(GeometryType::MultiPoint, $geometry->type);
        static::assertSame('{"type":"MultiPoint","coordinates":[[1.2,3.4],[5.6,7.8]]}', $geometry->toString());
    }

    public function test_creates_multi_line(): void
    {
        $geometry = Geometry::multiLine([[1.2, 3.4], [5.6, 7.8]]);

        static::assertSame(GeometryType::MultiLine, $geometry->type);
        static::assertSame('{"type":"MultiLinestring","coordinates":[[1.2,3.4],[5.6,7.8]]}', $geometry->toString());

        $geometry = Geometry::multiLineString(new Collection([[1.2, 3.4], [5.6, 7.8]]));

        static::assertSame(GeometryType::MultiLine, $geometry->type);
        static::assertSame('{"type":"MultiLinestring","coordinates":[[1.2,3.4],[5.6,7.8]]}', $geometry->toString());
    }

    public function test_creates_multi_polygon(): void
    {
        $geometry = Geometry::multiPolygon([[1.2, 3.4], [5.6, 7.8]]);

        static::assertSame(GeometryType::MultiPolygon, $geometry->type);
        static::assertSame('{"type":"MultiPolygon","coordinates":[[1.2,3.4],[5.6,7.8]]}', $geometry->toString());

        $geometry = Geometry::multiPolygon(new Collection([[1.2, 3.4], [5.6, 7.8]]));

        static::assertSame(GeometryType::MultiPolygon, $geometry->type);
        static::assertSame('{"type":"MultiPolygon","coordinates":[[1.2,3.4],[5.6,7.8]]}', $geometry->toString());
    }

    public function test_creates_new_collection(): void
    {
        $collection = Geometry::collection([
            Geometry::point(1.2, 3.4),
            Geometry::line([1.2, 3.4], [5.6, 7.8]),
        ]);

        static::assertSame('GeometryCollection', $collection->toArray()['type']);
        static::assertSame(
            '{"type":"GeometryCollection","geometries":[{"type":"Point","coordinates":[1.2,3.4]},{"type":"LineString","coordinates":[[1.2,3.4],[5.6,7.8]]}]}',
            (string) $collection
        );
    }
}
