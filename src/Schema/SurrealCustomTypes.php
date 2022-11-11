<?php

namespace Laragear\Surreal\Schema;

use Illuminate\Support\Fluent;
use RuntimeException;

trait SurrealCustomTypes
{
    /**
     * Accepted GeoJSON types.
     *
     * @var string[]
     */
    protected $geoJsonTypes = [
        'feature',
        'point',
        'line',
        'polygon',
        'multipoint',
        'multiline',
        'multipolygon',
        'collection',
    ];

    /**
     * Create the column definition for an any type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    public function typeAny(Fluent $column)
    {
        return 'any';
    }

    /**
     * Create the column definition for an array type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    public function typeArray(Fluent $column)
    {
        return 'array';
    }

    /**
     * Create the column definition for an duration type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    public function typeDuration(Fluent $column)
    {
        return 'duration';
    }

    /**
     * Create the column definition for an number type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    public function typeNumber(Fluent $column)
    {
        return 'number';
    }

    /**
     * Create the column definition for an object type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    public function typeObject(Fluent $column)
    {
        return 'object';
    }

    /**
     * Create the column definition for a record type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    public function typeRecord(Fluent $column)
    {
        return 'record(' . implode(',', $column->tables) . ')';
    }

    /**
     * Create the column definition for an geoJson type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    public function typeGeoJson(Fluent $column)
    {
        collect($column->types)->diff($this->geoJsonTypes)->when(
            fn ($types) => $types->isNotEmpty(),
            fn ($types) => throw new RuntimeException("Invalid GeoJSON types for $column->name: {$types->join(', ')}.")
        );

        return 'geometry(' . implode(',', $column->types) . ')';
    }
}
