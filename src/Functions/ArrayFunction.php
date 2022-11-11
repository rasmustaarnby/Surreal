<?php

namespace Laragear\Surreal\Functions;

class ArrayFunction
{
    /**
     * Combines all values from two arrays together.
     *
     * @param  string  $array
     * @param  string  $combine
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function combine(string $array, string $combine): SurrealFunction
    {
        return SurrealFunction::make('array::combine($?, $?)', [$array, $combine]);
    }

    /**
     * Returns the merged values from two arrays.
     *
     * @param  string  $array
     * @param  string  $concat
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function concat(string $array, string $concat): SurrealFunction
    {
        return SurrealFunction::make('array::concat($?, $?)', [$array, $concat]);
    }

    /**
     * Returns the difference between two arrays.
     *
     * @param  string  $array
     * @param  string  $difference
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function difference(string $array, string $difference): SurrealFunction
    {
        return SurrealFunction::make('array::difference($?, $?)', [$array, $difference]);
    }

    /**
     * Returns the difference between two arrays.
     *
     * @param  string  $array
     * @param  string  $difference
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function diff(string $array, string $difference): SurrealFunction
    {
        return $this->difference($array, $difference);
    }

    /**
     * Returns the unique items in an array.
     *
     * @param  string  $array
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function distinct(string $array): SurrealFunction
    {
        return SurrealFunction::make('array::distinct($?)', [$array]);
    }

    /**
     * Returns the values which intersect two arrays.
     *
     * @param  string  $array
     * @param  string  $intersect
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function intersect(string $array, string $intersect): SurrealFunction
    {
        return SurrealFunction::make('array::intersect($?, $?)', [$array, $intersect]);
    }

    /**
     * Returns the length of an array.
     *
     * @param  string  $array
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function len(string $array): SurrealFunction
    {
        return SurrealFunction::make('array::len($?)', [$array]);
    }

    /**
     * Returns the length of an array.
     *
     * @param  string  $array
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function length(string $array): SurrealFunction
    {
        return $this->len($array);
    }

    /**
     * Sorts the values in an array in ascending or descending order.
     *
     * @param  string  $array
     * @param  bool  $ascending
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function sort(string $array, bool $ascending = true): SurrealFunction
    {
        return $ascending
            ? SurrealFunction::make('array::sort::asc($?)', [$array])
            : SurrealFunction::make('array::sort::desc($?)', [$array]);
    }

    /**
     * Sorts the values in an array in ascending order.
     *
     * @param  string  $array
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function sortAsc(string $array): SurrealFunction
    {
        return $this->sort($array);
    }

    /**
     * Sorts the values in an array in descending order.
     *
     * @param  string  $array
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function sortDesc(string $array): SurrealFunction
    {
        return $this->sort($array, false);
    }

    /**
     * Returns the unique merged values from two arrays.
     *
     * @param  string  $array
     * @param  string  $union
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function union(string $array, string $union): SurrealFunction
    {
        return SurrealFunction::make('array::union($?, $?)', [$array, $union]);
    }
}
