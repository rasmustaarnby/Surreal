<?php

namespace Laragear\Surreal\Functions;

use function array_merge;

class StringFunction
{
    /**
     * Concatenates strings together.
     *
     * @param  string  ...$values
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function concat(string ...$values): SurrealFunction
    {
        $parameters = implode(', ', array_fill(0, count($values), '$?'));

        return SurrealFunction::make("string::concat($parameters)", $values);
    }

    /**
     * Concatenates strings together.
     *
     * @param  string  ...$values
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function concatenate(string ...$values): SurrealFunction
    {
        return $this->concat(...$values);
    }

    /**
     * Checks whether a string ends with another string.
     *
     * @param  string  $value
     * @param  string  $end
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function endsWith(string $value, string $end): SurrealFunction
    {
        return SurrealFunction::make('string::endsWith($?, $?)', [$value, $end]);
    }

    /**
     * Joins strings together with a delimiter.
     *
     * @param  string  $glue
     * @param  string  ...$values
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function join(string $glue, string ...$values): SurrealFunction
    {
        $parameters = '$?, ' . implode(', ', array_fill(0, count($values), '$?'));

        return SurrealFunction::make("string::join($parameters)", array_merge([$glue], $values));
    }

    /**
     * Returns the length of a string.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function length(string $value): SurrealFunction
    {
        return SurrealFunction::make('string::length($?)', [$value]);
    }

    /**
     * Returns the length of a string.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function len(string $value): SurrealFunction
    {
        return $this->length($value);
    }

    /**
     * Converts a string to lowercase.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function lowercase(string $value): SurrealFunction
    {
        return SurrealFunction::make('string::lowercase($?)', [$value]);
    }

    /**
     * Converts a string to lowercase.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function lc(string $value): SurrealFunction
    {
        return $this->lowercase($value);
    }

    /**
     * Repeats a string a number of times.
     *
     * @param  string  $value
     * @param  int  $times
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function repeat(string $value, int $times): SurrealFunction
    {
        return SurrealFunction::make("string::repeat($?, $times)", [$value]);
    }

    /**
     * Replaces an occurence of a string with another string.
     *
     * @param  string  $value
     * @param  string  $search
     * @param  string  $replace
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function replace(string $value, string $search, string $replace): SurrealFunction
    {
        return SurrealFunction::make('string::replace($?, $?, $?)', [$value, $search, $replace]);
    }

    /**
     * Reverses a string.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function reverse(string $value): SurrealFunction
    {
        return SurrealFunction::make('string::reverse($?)', [$value]);
    }

    /**
     * Extracts and returns a section of a string.
     *
     * @param  string  $value
     * @param  int  $start
     * @param  int  $length
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function slice(string $value, int $start, int $length): SurrealFunction
    {
        return SurrealFunction::make("string::slice($?, $start, $length)", [$value]);
    }

    /**
     * Converts a string into human and URL-friendly string.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function slug(string $value): SurrealFunction
    {
        return SurrealFunction::make("string::slug($?)", [$value]);
    }

    /**
     * Divides a string into an ordered list of substrings.
     *
     * @param  string  $value
     * @param  string  $delimiter
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function split(string $value, string $delimiter): SurrealFunction
    {
        return SurrealFunction::make('string::split($?, $?)', [$value, $delimiter]);
    }

    /**
     * Checks whether a string starts with another string.
     *
     * @param  string  $value
     * @param  string  $end
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function startsWith(string $value, string $end): SurrealFunction
    {
        return SurrealFunction::make('string::startsWith($?, $?)', [$value, $end]);
    }

    /**
     * Removes whitespace from the start and end of a string.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function trim(string $value): SurrealFunction
    {
        return SurrealFunction::make('string::trim($?)', [$value]);
    }

    /**
     * Converts a string to uppercase.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function uppercase(string $value): SurrealFunction
    {
        return SurrealFunction::make('string::uppercase($?)', [$value]);
    }

    /**
     * Converts a string to uppercase.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function uc(string $value): SurrealFunction
    {
        return $this->uppercase($value);
    }

    /**
     * Splits a string into an array of separate words.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function words(string $value): SurrealFunction
    {
        return SurrealFunction::make('string::words($?)', [$value]);
    }
}
