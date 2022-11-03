<?php

/** @noinspection  */

namespace Illuminate\Database\Query {

    /**
     * @method $this return(\Laragear\Surreal\Query\ReturnType|string|array ...$type)
     * @method $this returnNone()
     * @method $this timeout(\DateInterval|\Carbon\CarbonInterval|int $timeout)
     * @method $this parallel()
     * @method $this split(...$keys)
     * @method $this fetch(...$keys)
     * @method $this orderByCollate($field, $direction = 'asc')
     * @method $this orderByNumeric($field, $direction = 'asc')
     * @method \Laragear\Surreal\Query\Related|$this related(array|string ...$related)
     * @method \Illuminate\Support\Collection|null create(array $attributes = [])
     * @method \Laragear\Surreal\Query\RelateTo relateTo($relatedId)
     */
    class Builder
    {
        //
    }
}

namespace Illuminate\Database\Schema {

    /**
     * @method \Illuminate\Database\Schema\ColumnDefinition any($column)
     * @method \Illuminate\Database\Schema\ColumnDefinition array($column)
     * @method \Illuminate\Database\Schema\ColumnDefinition duration($column)
     * @method \Illuminate\Database\Schema\ColumnDefinition interval($column)
     * @method \Illuminate\Database\Schema\ColumnDefinition number($column)
     * @method \Illuminate\Database\Schema\ColumnDefinition object($column)
     * @method \Illuminate\Database\Schema\ColumnDefinition record($column, $tables)
     * @method \Illuminate\Database\Schema\ColumnDefinition geoJson($column, $types = ['feature'])
     */
    class Blueprint
    {
        //
    }
}
