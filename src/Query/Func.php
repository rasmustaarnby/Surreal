<?php

namespace Laragear\Surreal\Query;

use Laragear\Surreal\Functions\ArrayFunction;
use Laragear\Surreal\Functions\CryptoFunction;
use Laragear\Surreal\Functions\GeoFunction;
use Laragear\Surreal\Functions\HttpFunction;
use Laragear\Surreal\Functions\MathFunction;
use Laragear\Surreal\Functions\ParseFunction;
use Laragear\Surreal\Functions\RandomFunction;
use Laragear\Surreal\Functions\ScriptFunction;
use Laragear\Surreal\Functions\SessionFunction;
use Laragear\Surreal\Functions\StringFunction;
use Laragear\Surreal\Functions\SurrealFunction;
use Laragear\Surreal\Functions\TimeFunction;
use Laragear\Surreal\Functions\TypeFunction;
use Laragear\Surreal\Functions\ValidationFunction;

class Func
{
    /**
     * Manipulate an array of data.
     *
     * @see https://surrealdb.com/docs/surrealql/functions/array
     * @return \Laragear\Surreal\Functions\ArrayFunction
     */
    public static function array(): ArrayFunction
    {
        return new ArrayFunction();
    }

    /**
     * Count field values and expressions.
     *
     * @see https://surrealdb.com/docs/surrealql/functions/count
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public static function count(string $value): SurrealFunction
    {
        return SurrealFunction::make("count($value)");
    }

    /**
     * Hash, encrypt, or securely authenticate data.
     *
     * @see https://surrealdb.com/docs/surrealql/functions/crypto
     * @return \Laragear\Surreal\Functions\CryptoFunction
     */
    public static function crypto(): CryptoFunction
    {
        return new CryptoFunction();
    }

    /**
     * Work with and analysing geospatial data.
     *
     * @see https://surrealdb.com/docs/surrealql/functions/geo
     * @return \Laragear\Surreal\Functions\GeoFunction
     */
    public static function geo(): GeoFunction
    {
        return new GeoFunction();
    }

    /**
     * Submit remote web requests and create database-driven webhooks.
     *
     * @see https://surrealdb.com/docs/surrealql/functions/http
     * @return \Laragear\Surreal\Functions\HttpFunction
     */
    public static function http(): HttpFunction
    {
        return new HttpFunction();
    }

    /**
     * Check and validate the format of fields and values.
     *
     * @see https://surrealdb.com/docs/surrealql/functions/validation
     * @return \Laragear\Surreal\Functions\ValidationFunction
     */
    public static function is(): ValidationFunction
    {
        return new ValidationFunction();
    }

    /**
     * Check and validate the format of fields and values.
     *
     * @see https://surrealdb.com/docs/surrealql/functions/validation
     * @return \Laragear\Surreal\Functions\ValidationFunction
     */
    public static function validate(): ValidationFunction
    {
        return static::is();
    }

    /**
     * Analyze numeric data and numeric collections.
     *
     * @see https://surrealdb.com/docs/surrealql/functions/math
     * @return \Laragear\Surreal\Functions\MathFunction
     */
    public static function math(): MathFunction
    {
        return new MathFunction();
    }

    /**
     * Parse email addresses and URL web addresses.
     *
     * @see https://surrealdb.com/docs/surrealql/functions/parse
     * @return \Laragear\Surreal\Functions\ParseFunction
     */
    public static function parse(): ParseFunction
    {
        return new ParseFunction();
    }

    /**
     * Generate random data values.
     *
     * @see https://surrealdb.com/docs/surrealql/functions/rand
     * @return \Laragear\Surreal\Functions\RandomFunction
     */
    public static function rand(): RandomFunction
    {
        return new RandomFunction();
    }

    /**
     * Generate random data values.
     *
     * @see https://surrealdb.com/docs/surrealql/functions/rand
     * @return \Laragear\Surreal\Functions\RandomFunction
     */
    public static function random(): RandomFunction
    {
        return static::rand();
    }

    /**
     * Retrieve information about the current database session.
     *
     * @see https://surrealdb.com/docs/surrealql/functions/session
     * @return \Laragear\Surreal\Functions\SessionFunction
     */
    public static function session(): SessionFunction
    {
        return new SessionFunction();
    }

    /**
     * Manipulate text and string values.
     *
     * @see https://surrealdb.com/docs/surrealql/functions/string
     * @return \Laragear\Surreal\Functions\StringFunction
     */
    public static function string(): StringFunction
    {
        return new StringFunction();
    }

    /**
     * Manipulate text and string values.
     *
     * @see https://surrealdb.com/docs/surrealql/functions/string
     * @return \Laragear\Surreal\Functions\StringFunction
     */
    public static function str(): StringFunction
    {
        return static::string();
    }

    /**
     * Manipulate datetime values.
     *
     * @see https://surrealdb.com/docs/surrealql/functions/time
     * @return \Laragear\Surreal\Functions\TimeFunction
     */
    public static function time(): TimeFunction
    {
        return new TimeFunction();
    }

    /**
     * Manipulate datetime values.
     *
     * @see https://surrealdb.com/docs/surrealql/functions/time
     * @return \Laragear\Surreal\Functions\TimeFunction
     */
    public static function date(): TimeFunction
    {
        return static::time();
    }

    /**
     * Generate and coercing data to specific data types
     *
     * @see https://surrealdb.com/docs/surrealql/functions/type
     * @return \Laragear\Surreal\Functions\TypeFunction
     */
    public static function type(): TypeFunction
    {
        return new TypeFunction();
    }

    /**
     * Create an advanced ES2020 JavaScript script.
     *
     * @see https://surrealdb.com/docs/surrealql/functions/script
     * @param  string  $script
     * @return \Laragear\Surreal\Functions\ScriptFunction
     */
    public static function script(string $script): ScriptFunction
    {
        return new ScriptFunction($script);
    }

    /**
     * Create an advanced ES2020 JavaScript script.
     *
     * @see https://surrealdb.com/docs/surrealql/functions/script
     * @param  string  $script
     * @return \Laragear\Surreal\Functions\ScriptFunction
     */
    public static function js(string $script): ScriptFunction
    {
        return static::script($script);
    }
}
