<?php

namespace Laragear\Surreal\Functions;

class TypeFunction
{
    /**
     * Converts a value into a boolean.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function bool(string $value): SurrealFunction
    {
        return SurrealFunction::make('type::bool($?)', [$value]);
    }

    /**
     * Converts a value into a datetime.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function datetime(string $value): SurrealFunction
    {
        return SurrealFunction::make('type::datetime($?)', [$value]);
    }

    /**
     * Converts a value into a decimal.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function decimal(string $value): SurrealFunction
    {
        return SurrealFunction::make('type::decimal($?)', [$value]);
    }

    /**
     * Converts a value into a duration.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function duration(string $value): SurrealFunction
    {
        return SurrealFunction::make('type::duration($?)', [$value]);
    }

    /**
     * Converts a value into a floating point number.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function float(string $value): SurrealFunction
    {
        return SurrealFunction::make('type::float($?)', [$value]);
    }

    /**
     * Converts a value into an integer.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function int(string $value): SurrealFunction
    {
        return SurrealFunction::make('type::int($?)', [$value]);
    }

    /**
     * Converts a value into an integer.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function integer(string $value): SurrealFunction
    {
        return $this->int($value);
    }

    /**
     * Converts a value into a number.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function number(string $value): SurrealFunction
    {
        return SurrealFunction::make('type::number($?)', [$value]);
    }

    /**
     * Converts a value into a geometry point.
     *
     * @param  string  $value
     * @param  string|null  $latitude
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function point(string $value, string $latitude = null): SurrealFunction
    {
        if ($latitude !== null) {
            return SurrealFunction::make('type::point($?, $?)', [$value, $latitude]);
        }

        return SurrealFunction::make('type::point($?)', [$value]);
    }

    /**
     * Converts a value into a regular expression.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function regex(string $value): SurrealFunction
    {
        return SurrealFunction::make('type::regex($?)', [$value]);
    }

    /**
     * Converts a value into a string.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function string(string $value): SurrealFunction
    {
        return SurrealFunction::make('type::string($?)', [$value]);
    }

    /**
     * Converts a value into a string.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function str(string $value): SurrealFunction
    {
        return $this->string($value);
    }

    /**
     * Converts a value into a table.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function table(string $value): SurrealFunction
    {
        return SurrealFunction::make('type::table($?)', [$value]);
    }

    /**
     * Converts a value into a record pointer.
     *
     * @param  string  $key
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function thing(string $key, string $value): SurrealFunction
    {
        return SurrealFunction::make('type::thing($?, $?)', [$key, $value]);
    }

    /**
     * Converts a value into a record pointer.
     *
     * @param  string  $key
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function record(string $key, string $value): SurrealFunction
    {
        return $this->thing($key, $value);
    }
}
