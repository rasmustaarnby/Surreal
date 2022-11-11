<?php

namespace Laragear\Surreal\Types;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use Stringable;
use function array_map;
use function json_encode;

class Geometry implements Stringable, JsonSerializable, Arrayable, Jsonable
{
    /**
     * Create a new Geometry type.
     *
     * @param  \Laragear\Surreal\Types\GeometryType  $type
     * @param  array  $coordinates
     */
    public function __construct(public GeometryType $type, public array $coordinates)
    {
        //
    }

    /**
     * Get the instance as an array.
     *
     * @return array{type:string,coordinates:array}
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type->value,
            'coordinates' => $this->coordinates,
        ];
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Return a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return $this->__toString();
    }

    /**
     * Return a string representation of the object.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * Create a GeoJSON Point geometry.
     *
     * @param  float  $longitude
     * @param  float  $latitude
     * @return static
     */
    public static function point(float $longitude, float $latitude): static
    {
        return new static(GeometryType::Point, [$longitude, $latitude]);
    }

    /**
     * Create a GeoJSON Line geometry.
     *
     * @param  array  $startCoordinates
     * @param  array  $endCoordinates
     * @return static
     */
    public static function line(array $startCoordinates, array $endCoordinates): static
    {
        return new static(GeometryType::Line, [$startCoordinates, $endCoordinates]);
    }

    /**
     * Create a GeoJSON polygon geometry.
     *
     * @param  array  $points
     * @return static
     */
    public static function polygon(array $points): static
    {
        return new static(GeometryType::Polygon, [$points]);
    }

    /**
     * Create a GeoJSON multiPoint geometry.
     *
     * @param  \Illuminate\Contracts\Support\Arrayable<int, \Laragear\Surreal\Types\Geometry|float>|array{\Laragear\Surreal\Types\Geometry|float}  $points
     * @return static
     */
    public static function multiPoint(Arrayable|array $points): static
    {
        if ($points instanceof Arrayable) {
            $points = static::unwrapCoordinates($points);
        }

        return new static(GeometryType::MultiPoint, $points);
    }

    /**
     * Create a GeoJSON multiLine geometry.
     *
     * @param  \Illuminate\Contracts\Support\Arrayable<int, \Laragear\Surreal\Types\Geometry|float>|array{\Laragear\Surreal\Types\Geometry|float}  $lines
     * @return static
     */
    public static function multiLine(Arrayable|array $lines): static
    {
        if ($lines instanceof Arrayable) {
            $lines = static::unwrapCoordinates($lines);
        }

        return new static(GeometryType::MultiLine, $lines);
    }

    /**
     * Create a GeoJSON multiLine geometry.
     *
     * @param  \Illuminate\Contracts\Support\Arrayable<int, \Laragear\Surreal\Types\Geometry|float>|array{\Laragear\Surreal\Types\Geometry|float}  $lines
     * @return static
     */
    public static function multiLineString(Arrayable|array $lines): static
    {
        return static::multiLine($lines);
    }

    /**
     * Create a GeoJSON multiPolygon geometry.
     *
     * @param  \Illuminate\Contracts\Support\Arrayable<int, \Laragear\Surreal\Types\Geometry|float>|array{\Laragear\Surreal\Types\Geometry|float}  $polygons
     * @return static
     */
    public static function multiPolygon(Arrayable|array $polygons): static
    {
        if ($polygons instanceof Arrayable) {
            $polygons = static::unwrapCoordinates($polygons);
        }

        return new static(GeometryType::MultiPolygon, $polygons);
    }

    /**
     * Create multiple different geometry types into a collection.
     *
     * @param  \Illuminate\Contracts\Support\Arrayable<int, \Laragear\Surreal\Types\Geometry>|array{\Laragear\Surreal\Types\Geometry}  $geometries
     * @return \Laragear\Surreal\Types\GeometryCollection
     */
    public static function collection(Arrayable|array $geometries = []): GeometryCollection
    {
        return new GeometryCollection($geometries);
    }

    /**
     * Unwraps the coordinates of list of coordinates
     *
     * @param  \Illuminate\Contracts\Support\Arrayable<int, \Laragear\Surreal\Types\Geometry>  $geometry
     * @return array[]
     */
    protected static function unwrapCoordinates(Arrayable $geometry): array
    {
        return array_map(static function (array $coordinates): array {
            return $coordinates;
        }, $geometry->toArray());
    }
}
