<?php

namespace Laragear\Surreal;

use Illuminate\Support\Str;
use function substr_count;

/**
 * @internal
 */
class Surreal
{
    /**
     * Check if the id is a SurrealDB id.
     *
     * @param  string  $id
     * @return bool
     */
    public static function isId(string $id): bool
    {
        return ! str_starts_with($id, ':')
            && substr_count($id, ':') === 1;
    }

    /**
     * Check if the id is not a SurrealDB id.
     *
     * @param  string  $id
     * @return void
     */
    public static function isNotId(string $id): bool
    {
        return ! self::isId($id);
    }

    /**
     * Check if an edge has arrows.
     *
     * @param  string  $edge
     * @return bool
     */
    public static function isEdge(string $edge)
    {
        return Str::startsWith($edge, ['<-', '->']);
    }

    /**
     * Check if an edge has no arrows.
     *
     * @param  string  $edge
     * @return bool
     */
    public static function isNotEdge(string $edge)
    {
        return ! self::isEdge($edge);
    }
}
