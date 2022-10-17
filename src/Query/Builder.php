<?php

namespace Laragear\Surreal\Query;

use Carbon\CarbonInterval;
use Closure;
use DateInterval;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use function func_get_args;
use function func_num_args;
use function is_array;
use function is_int;
use function is_string;
use function ksort;
use function reset;

/**
 * @internal
 *
 * @mixin \Illuminate\Database\Query\Builder
 * @property-read \Laragear\Surreal\Query\SurrealGrammar $grammar
 * @property-read \Laragear\Surreal\SurrealConnection $connection
 */
class Builder
{
    /**
     * Creates a record in SurrealDB.
     *
     * @return \Closure
     */
    public function create(): Closure
    {
        return function (iterable $values = []): Collection {
            if (is_array(reset($values))) {
                ksort($values);
            }

            $this->applyBeforeQueryCallbacks();

            return $this->connection->create(
                $this->grammar->compileCreate($this, $values), $values,
            );
        };
    }

    /**
     * Set the return to a given type.
     *
     * @return \Closure
     */
    public function return(): Closure
    {
        return function (ReturnType|string|array $type): QueryBuilder {
            // This spaghetti code does the following:
            //   1. If the type is a return type string, cast it to the enum.               ("none")
            //   2. If is the enum itself, set it.                                          (ReturnType:Default)
            //   3. If there are more arguments, set them all.                              ("foo", "bar"...)
            //   4. The default is a string or array, set them "all" as list.               ("foo")
            $this->grammar->return = match (true) {
                is_string($type) && ReturnType::tryFrom($type) => ReturnType::from($type),
                $type instanceof ReturnType => $type,
                func_num_args() > 1 => func_get_args(),
                default => (array) $type,
            };

            return $this;
        };
    }

    /**
     * Set the return to none.
     *
     * @return \Closure
     */
    public function returnNone(): Closure
    {
        return function (): QueryBuilder {
            /** @var $this \Illuminate\Database\Query\Builder */
            return $this->return(ReturnType::None);
        };
    }

    /**
     * Sets a timeout for the query.
     *
     * @return \Closure
     */
    public function timeout(): Closure
    {
        return function (DateInterval|CarbonInterval|int $duration): QueryBuilder {
            $this->grammar->timeout = match (true) {
                is_int($duration) => CarbonInterval::create(0, seconds: $duration),
                $duration instanceof DateInterval => CarbonInterval::instance($duration),
                default => $duration
            };

            return $this;
        };
    }

    /**
     * Sets the FETCH and relations to be retrieved in parallel.
     *
     * @return \Closure
     */
    public function parallel(): Closure
    {
        return function (): QueryBuilder {
            $this->grammar->parallel = true;

            return $this;
        };
    }
}
