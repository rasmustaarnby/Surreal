<?php

namespace Laragear\Surreal\Functions;

class ValidationFunction
{
    /**
     * Checks whether a value has only alphanumeric characters.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function alphanum(string $value): SurrealFunction
    {
        return SurrealFunction::make('is::alphanum($?)', [$value]);
    }

    /**
     * Checks whether a value has only alpha characters.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function alpha(string $value): SurrealFunction
    {
        return SurrealFunction::make('is::alpha($?)', [$value]);
    }

    /**
     * Checks whether a value has only ascii characters.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function ascii(string $value): SurrealFunction
    {
        return SurrealFunction::make('is::ascii($?)', [$value]);
    }

    /**
     * Checks whether a value is a domain.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function domain(string $value): SurrealFunction
    {
        return SurrealFunction::make('is::domain($?)', [$value]);
    }

    /**
     * Checks whether a value is an email.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function email(string $value): SurrealFunction
    {
        return SurrealFunction::make('is::email($?)', [$value]);
    }

    /**
     * Checks whether a value is hexadecimal.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function hexadecimal(string $value): SurrealFunction
    {
        return SurrealFunction::make('is::hexadecimal($?)', [$value]);
    }

    /**
     * Checks whether a value is a latitude value.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function latitude(string $value): SurrealFunction
    {
        return SurrealFunction::make('is::latitude($?)', [$value]);
    }

    /**
     * Checks whether a value is a longitude value.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function longitude(string $value): SurrealFunction
    {
        return SurrealFunction::make('is::longitude($?)', [$value]);
    }

    /**
     * Checks whether a value has only numeric characters.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function numeric(string $value): SurrealFunction
    {
        return SurrealFunction::make('is::numeric($?)', [$value]);
    }

    /**
     * Checks whether a value matches a semver version.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function semver(string $value): SurrealFunction
    {
        return SurrealFunction::make('is::semver($?)', [$value]);
    }

    /**
     * Checks whether a value is a UUID.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function uuid(string $value): SurrealFunction
    {
        return SurrealFunction::make('is::uuid($?)', [$value]);
    }
}
