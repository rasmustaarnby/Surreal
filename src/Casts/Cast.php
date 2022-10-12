<?php

namespace Laragear\Surreal\Casts;

use Stringable;
use function stripslashes;

class Cast implements Stringable
{
    /**
     * Create a new Cast instance.
     *
     * @param  \Laragear\Surreal\Casts\CastType  $castType
     * @param  mixed  $valueOrStatement
     */
    final public function __construct(protected CastType $castType, protected mixed $valueOrStatement)
    {
        //
    }

    /**
     * Return the string representation of the object.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->castType->value . ' ' . stripslashes($this->valueOrStatement);
    }

    /**
     * Casts the value or statement as bool.
     *
     * @param  mixed  $valueOrStatement
     * @return \Laragear\Surreal\Casts\Cast
     */
    public static function bool(mixed $valueOrStatement): Cast
    {
        return new static(CastType::Bool, $valueOrStatement);
    }

    /**
     * Casts the value or statement as int.
     *
     * @param  mixed  $valueOrStatement
     * @return \Laragear\Surreal\Casts\Cast
     */
    public static function int(mixed $valueOrStatement): Cast
    {
        return new static(CastType::Int, $valueOrStatement);
    }

    /**
     * Casts the value or statement as integer.
     *
     * @param  mixed  $valueOrStatement
     * @return \Laragear\Surreal\Casts\Cast
     */
    public static function integer(mixed $valueOrStatement): Cast
    {
        return static::int($valueOrStatement);
    }

    /**
     * Casts the value or statement as float.
     *
     * @param  mixed  $valueOrStatement
     * @return \Laragear\Surreal\Casts\Cast
     */
    public static function float(mixed $valueOrStatement): Cast
    {
        return new static(CastType::Float, $valueOrStatement);
    }

    /**
     * Casts the value or statement as string.
     *
     * @param  mixed  $valueOrStatement
     * @return \Laragear\Surreal\Casts\Cast
     */
    public static function string(mixed $valueOrStatement): Cast
    {
        return new static(CastType::String, $valueOrStatement);
    }

    /**
     * Casts the value or statement as str.
     *
     * @param  mixed  $valueOrStatement
     * @return \Laragear\Surreal\Casts\Cast
     */
    public static function str(mixed $valueOrStatement): Cast
    {
        return static::string($valueOrStatement);
    }

    /**
     * Casts the value or statement as number.
     *
     * @param  mixed  $valueOrStatement
     * @return \Laragear\Surreal\Casts\Cast
     */
    public static function number(mixed $valueOrStatement): Cast
    {
        return new static(CastType::Number, $valueOrStatement);
    }

    /**
     * Casts the value or statement as numeric.
     *
     * @param  mixed  $valueOrStatement
     * @return \Laragear\Surreal\Casts\Cast
     */
    public static function numeric(mixed $valueOrStatement): Cast
    {
        return static::number($valueOrStatement);
    }

    /**
     * Casts the value or statement as decimal.
     *
     * @param  mixed  $valueOrStatement
     * @return \Laragear\Surreal\Casts\Cast
     */
    public static function decimal(mixed $valueOrStatement): Cast
    {
        return new static(CastType::Decimal, $valueOrStatement);
    }

    /**
     * Casts the value or statement as datetime.
     *
     * @param  mixed  $valueOrStatement
     * @return \Laragear\Surreal\Casts\Cast
     */
    public static function datetime(mixed $valueOrStatement): Cast
    {
        return new static(CastType::Datetime, $valueOrStatement);
    }

    /**
     * Casts the value or statement as duration.
     *
     * @param  mixed  $valueOrStatement
     * @return \Laragear\Surreal\Casts\Cast
     */
    public static function duration(mixed $valueOrStatement): Cast
    {
        return new static(CastType::Duration, $valueOrStatement);
    }
}