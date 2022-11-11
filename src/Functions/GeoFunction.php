<?php

namespace Laragear\Surreal\Functions;

use Laragear\Surreal\Types\Geometry;

class GeoFunction
{
    /**
     * Calculates the area of a geometry.
     *
     * @param  \Laragear\Surreal\Types\Geometry|string  $geometry
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function area(Geometry|string $geometry): SurrealFunction
    {
        return SurrealFunction::make('geo::area($?)', [$geometry]);
    }

    /**
     * Calculates the bearing between two geolocation points.
     *
     * @param  string  $first
     * @param  string  $second
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function bearing(string $first, string $second): SurrealFunction
    {
        return SurrealFunction::make('geo::bearing($?, $?)', [$first, $second]);
    }

    /**
     * Calculates the centroid of a geometry.
     *
     * @param  \Laragear\Surreal\Types\Geometry|string  $polygon
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function centroid(Geometry|string $polygon): SurrealFunction
    {
        return SurrealFunction::make('geo::centroid($?)', [$polygon]);
    }

    /**
     * Calculates the distance between two geolocation points.
     *
     * @param  string  $first
     * @param  string  $second
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function distance(string $first, string $second): SurrealFunction
    {
        return SurrealFunction::make('geo::distance($?, $?)', [$first, $second]);
    }

    /**
     * Decodes a geohash attribute from the query into a geometry point.
     *
     * @param  string  $hash
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function hashDecode(string $hash): SurrealFunction
    {
        return SurrealFunction::make('geo::hash::decode($?)', [$hash]);
    }

    /**
     * Encodes a geometry point attribute from the query into a geohash.
     *
     * @param  string  $hash
     * @param  int|null  $accuracy
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function hashEncode(string $hash, int $accuracy = null): SurrealFunction
    {
        $string = $accuracy === null
            ? 'geo::hash::encode($?)'
            : 'geo::hash::encode($?, $?)';

        return SurrealFunction::make($string, [$hash, $accuracy]);
    }
}
