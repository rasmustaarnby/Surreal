<?php

namespace Laragear\Surreal\Schema;

use Closure;

class Blueprint
{
    /**
     * Create a new any column.
     *
     * @return \Closure
     */
    public function any(): Closure
    {
        return function($column) {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->addColumn('any', $column);
        };
    }

    /**
     * Create a new array column.
     *
     * @return \Closure
     */
    public function array(): Closure
    {
        return function($column) {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->addColumn('array', $column);
        };
    }

    /**
     * Create a new duration column.
     *
     * @return \Closure
     */
    public function duration(): Closure
    {
        return function($column) {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->addColumn('duration', $column);
        };
    }

    /**
     * Create a new duration column.
     *
     * @return \Closure
     */
    public function interval(): Closure
    {
        return $this->duration();
    }

    /**
     * Create a new number column.
     *
     * @return \Closure
     */
    public function number(): Closure
    {
        return function($column) {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->addColumn('number', $column);
        };
    }

    /**
     * Create a new object column.
     *
     * @return \Closure
     */
    public function object(): Closure
    {
        return function($column) {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->addColumn('object', $column);
        };
    }

    /**
     * Create a new record column.
     *
     * @return \Closure
     */
    public function record(): Closure
    {
        return function($column, $tables) {
            $tables = (array) $tables;

            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->addColumn('record', $column, compact('tables'));
        };
    }

    /**
     * Create a new geometry column.
     *
     * @return \Closure
     */
    public function geoJson(): Closure
    {
        return function($column, $types = ['feature']) {
            $types = (array) $types;

            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->addColumn('geoJson', $column, compact('types'));
        };
    }
}
