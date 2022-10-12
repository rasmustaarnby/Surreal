<?php

namespace Laragear\Surreal\Casts;

use Carbon\CarbonInterval;
use DateInterval;
use DateTimeInterface;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use function is_string;
use function str_contains;

enum CastType: string implements CastsAttributes
{
    case Bool = '<bool>';
    case Int = '<int>';
    case Float = '<float>';
    case String = '<string>';
    case Number = '<number>';
    case Decimal = '<decimal>';
    case Datetime = '<datetime>';
    case Duration = '<duration>';

    /**
     * Transform the attribute from the underlying model values.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return bool|int|float|string|null
     */
    public function get($model, string $key, $value, array $attributes): bool|int|float|string|null
    {
        if (null === $value) {
            return null;
        }

        return match ($this) {
            self::Bool => (bool) $value,
            self::Int => (int) $value,
            self::Float => (float) $value,
            self::Number => str_contains($value, '.') ? (float) $value : (int) $value,
            self::Duration => CarbonInterval::fromString((string) $value),
            self::Datetime => Date::parse((string) $value),
            default => (string) $value,
        };
    }

    /**
     * Transform the attribute to its underlying model values.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return bool|int|float|string|null
     */
    public function set($model, string $key, $value, array $attributes): bool|int|float|string|null
    {
        if (null === $value) {
            return null;
        }

        return match ($this) {
            self::Bool => (bool) $value,
            self::Int => (int) $value,
            self::Float => (float) $value,
            self::Number => str_contains($value, '.') ? (float) $value : (int) $value,
            default => (string) $value,
        };
    }
}