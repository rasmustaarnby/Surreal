<?php

namespace Laragear\Surreal\Functions;

use RuntimeException;

class TimeFunction
{
    /**
     * Group possible values.
     *
     * @var string[]
     */
    public const GROUP_VALUES = ['year', 'month', 'day', 'hour', 'minute', 'second'];

    /**
     * Extracts the day as a number from a datetime.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function day(string $value): SurrealFunction
    {
        return SurrealFunction::make('time::day($?)', [$value]);
    }

    /**
     * Rounds a datetime down by a specific duration.
     *
     * @param  string  $value
     * @param  string  $duration
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function floor(string $value, string $duration): SurrealFunction
    {
        return SurrealFunction::make('time::floor($?, $?)', [$value, $duration]);
    }

    /**
     * Groups a datetime by a particular time interval.
     *
     * @param  string  $value
     * @param  string  $group
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function group(string $value, string $group): SurrealFunction
    {
        if (!in_array($group, static::GROUP_VALUES, true)) {
            throw new RuntimeException(
                'The time group must be of one of ' . implode(', ', static::GROUP_VALUES) . ", [$group] given."
            );
        }

        return SurrealFunction::make('time::group($?, $?)', [$value, $group]);
    }

    /**
     * Extracts the hour as a number from a datetime.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function hour(string $value): SurrealFunction
    {
        return SurrealFunction::make('time::hour($?)', [$value]);
    }

    /**
     * Extracts the minutes as a number from a datetime.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function mins(string $value): SurrealFunction
    {
        return SurrealFunction::make('time::mins($?)', [$value]);
    }

    /**
     * Extracts the minutes as a number from a datetime.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function minutes(string $value)
    {
        return $this->mins($value);
    }

    /**
     * Extracts the month as a number from a datetime.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function month(string $value): SurrealFunction
    {
        return SurrealFunction::make('time::month($?)', [$value]);
    }

    /**
     * Returns the number of nanoseconds since the UNIX epoch.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function nano(string $value): SurrealFunction
    {
        return SurrealFunction::make('time::nano($?)', [$value]);
    }

    /**
     * Returns the current datetime.
     *
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function now(): SurrealFunction
    {
        return SurrealFunction::make('time::now()');
    }

    /**
     * Rounds a datetime up by a specific duration.
     *
     * @param  string  $value
     * @param  string  $duration
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function round(string $value, string $duration): SurrealFunction
    {
        return SurrealFunction::make('time::round($?, $?)', [$value, $duration]);
    }

    /**
     * Extracts the secs as a number from a datetime.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function secs(string $value): SurrealFunction
    {
        return SurrealFunction::make('time::secs($?)', [$value]);
    }

    /**
     * Extracts the secs as a number from a datetime.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function seconds(string $value)
    {
        return $this->secs($value);
    }

    /**
     * Returns the number of seconds since the UNIX epoch.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function unix(string $value): SurrealFunction
    {
        return SurrealFunction::make('time::unix($?)', [$value]);
    }

    /**
     * Extracts the week day as a number from a datetime.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function wday(string $value): SurrealFunction
    {
        return SurrealFunction::make('time::wday($?)', [$value]);
    }

    /**
     * Extracts the week day as a number from a datetime.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function weekDay(string $value)
    {
        return $this->wday($value);
    }

    /**
     * Extracts the week as a number from a datetime.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function week(string $value): SurrealFunction
    {
        return SurrealFunction::make('time::week($?)', [$value]);
    }

    /**
     * Extracts the yday as a number from a datetime.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function yday(string $value): SurrealFunction
    {
        return SurrealFunction::make('time::yday($?)', [$value]);
    }

    /**
     * Extracts the yday as a number from a datetime.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function yearDay(string $value)
    {
        return $this->yday($value);
    }

    /**
     * Extracts the year as a number from a datetime.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function year(string $value): SurrealFunction
    {
        return SurrealFunction::make('time::year($?)', [$value]);
    }
}
