<?php

namespace Laragear\Surreal\Functions;

use RuntimeException;

class RandomFunction
{
    /**
     * Generates and returns a random boolean.
     *
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function bool(): SurrealFunction
    {
        return SurrealFunction::make('rand::bool()');
    }

    /**
     * Randomly picks a value from the specified values.
     *
     * @param  string  ...$values
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function enum(string ...$values): SurrealFunction
    {
        $args = implode(', ', array_fill(0, count($values), '$?'));

        return SurrealFunction::make("rand::enum($args)", $values);
    }

    /**
     * Generates and returns a random floating point number.
     *
     * @param  float|null  $min
     * @param  float|null  $max
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function float(float $min = null, float $max = null): SurrealFunction
    {
        if (func_num_args() === 1) {
            throw new RuntimeException('The float function parameters requires both or none.');
        }

        $parameters = implode(', ', func_get_args());

        return SurrealFunction::make("rand::float($parameters)");
    }

    /**
     * Generates and returns a random guid.
     *
     * @param  int|null  $length
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function guid(int $length = null): SurrealFunction
    {
        return SurrealFunction::make("rand::guid($length)");
    }

    /**
     * Generates and returns a random integer.
     *
     * @param  float|null  $min
     * @param  float|null  $max
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function int(float $min = null, float $max = null): SurrealFunction
    {
        if (func_num_args() === 1) {
            throw new RuntimeException('The int function parameters requires both or none.');
        }

        $parameters = implode(', ', func_get_args());

        return SurrealFunction::make("rand::int($parameters)");
    }

    /**
     * Generates and returns a random string.
     *
     * @param  int|null  $length
     * @param  int|null  $max
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function string(int $length = null, int $max = null): SurrealFunction
    {
        $parameters = implode(', ', func_get_args());

        return SurrealFunction::make("rand::string($parameters)");
    }

    /**
     * Generates and returns a random datetime.
     *
     * @param  int|null  $min
     * @param  int|null  $max
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function time(int $min = null, int $max = null): SurrealFunction
    {
        if (func_num_args() === 1) {
            throw new RuntimeException('The time function parameters requires both or none.');
        }

        $parameters = implode(', ', func_get_args());

        return SurrealFunction::make("rand::time($parameters)");
    }

    /**
     * Generates and returns a random UUID.
     *
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function uuid(): SurrealFunction
    {
        return SurrealFunction::make("rand::uuid()");
    }
}
