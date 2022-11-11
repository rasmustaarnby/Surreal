<?php

namespace Laragear\Surreal\Functions;

class MathFunction
{
    /**
     * Returns the absolute value of a number.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function abs(string $value): SurrealFunction
    {
        return SurrealFunction::make('math::abs($?)', [$value]);
    }

    /**
     * Returns the absolute value of a number.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function absolute(string $value): SurrealFunction
    {
        return $this->abs($value);
    }

    /**
     * Rounds a number up to the next largest integer.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function ceil(string $value): SurrealFunction
    {
        return SurrealFunction::make('math::ceil($?)', [$value]);
    }

    /**
     * Returns a number with the specified number of decimal places.
     *
     * @param  string  $value
     * @param  int  $decimals
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function fixed(string $value, int $decimals): SurrealFunction
    {
        return SurrealFunction::make('math::fixed($?, $?)', [$value, $decimals]);
    }

    /**
     * Rounds a number down to the next largest integer.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function floor(string $value): SurrealFunction
    {
        return SurrealFunction::make('math::floor($?)', [$value]);
    }

    /**
     * Returns the maximum number in a set of numbers.
     *
     * @param  string  $array
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function max(string $array): SurrealFunction
    {
        return SurrealFunction::make('math::max($?)', [$array]);
    }

    /**
     * Returns the mean of a set of numbers.
     *
     * @param  string  $array
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function mean(string $array): SurrealFunction
    {
        return SurrealFunction::make('math::mean($?)', [$array]);
    }

    /**
     * Returns the median of a set of numbers.
     *
     * @param  string  $array
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function median(string $array): SurrealFunction
    {
        return SurrealFunction::make('math::median($?)', [$array]);
    }

    /**
     * Returns the minimum number in a set of numbers.
     *
     * @param  string  $array
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function min(string $array): SurrealFunction
    {
        return SurrealFunction::make('math::min($?)', [$array]);
    }

    /**
     * Returns the product of a set of numbers.
     *
     * @param  string  $array
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function product(string $array): SurrealFunction
    {
        return SurrealFunction::make('math::product($?)', [$array]);
    }

    /**
     * Rounds a number up or down to the nearest integer.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function round(string $value): SurrealFunction
    {
        return SurrealFunction::make('math::round($?)', [$value]);
    }

    /**
     * Returns the square root of a number.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function sqrt(string $value): SurrealFunction
    {
        return SurrealFunction::make('math::sqrt($?)', [$value]);
    }

    /**
     * Returns the square root of a number.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function squareRoot(string $value): SurrealFunction
    {
        return $this->sqrt($value);
    }

    /**
     * Returns the total sum of a set of numbers.
     *
     * @param  string  $array
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function sum(string $array): SurrealFunction
    {
        return SurrealFunction::make('math::sum($?)', [$array]);
    }
}
