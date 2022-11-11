<?php

namespace Laragear\Surreal\Schema;

use BadMethodCallException;
use Illuminate\Database\Query\Expression;
use Laragear\Surreal\Query\Func;
use function method_exists;
use function sprintf;

/**
 * @method \Laragear\Surreal\Functions\ArrayFunction array()
 * @method \Laragear\Surreal\Functions\CryptoFunction crypto()
 * @method \Laragear\Surreal\Functions\GeoFunction geo()
 * @method \Laragear\Surreal\Functions\HttpFunction http()
 * @method \Laragear\Surreal\Functions\MathFunction is()
 * @method \Laragear\Surreal\Functions\ParseFunction math()
 * @method \Laragear\Surreal\Functions\RandomFunction parse()
 * @method \Laragear\Surreal\Functions\ScriptFunction rand()
 * @method \Laragear\Surreal\Functions\ScriptFunction random()
 * @method \Laragear\Surreal\Functions\SessionFunction session()
 * @method \Laragear\Surreal\Functions\StringFunction string()
 * @method \Laragear\Surreal\Functions\StringFunction str()
 * @method \Laragear\Surreal\Functions\TimeFunction time()
 * @method \Laragear\Surreal\Functions\TypeFunction type()
 * @method \Laragear\Surreal\Functions\ValidationFunction js()
 * @method \Laragear\Surreal\Functions\ValidationFunction script()
 */
class FieldAssertion
{
    /**
     * Counts a row, or whether a given value is truthy.
     *
     * @param  string  $value
     * @return \Illuminate\Database\Query\Expression
     */
    public function count(string $value): Expression
    {
        return new Expression("count($value)");
    }

    /**
     * Handle dynamic calls to a function to assert.
     *
     * @param  string  $method
     * @param  array  $arguments
     * @return mixed
     */
    public function __call(string $method, array $arguments)
    {
        if (method_exists(Func::class, $method)) {
            return Func::{$method}(...$arguments);
        }

        throw new BadMethodCallException(sprintf(
            'Call to undefined method %s::%s()', static::class, $method
        ));
    }
}
