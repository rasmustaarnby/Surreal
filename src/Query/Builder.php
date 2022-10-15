<?php

namespace Laragear\Surreal\Query;

use Closure;
use Illuminate\Support\Collection;
use function is_array;
use function ksort;
use function reset;

/**
 * @internal
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
            /** @var $this \Illuminate\Database\Query\Builder */
            if (is_array(reset($values))) {
                ksort($values);
            }

            $this->applyBeforeQueryCallbacks();

            return $this->connection->create(
                $this->grammar->compileCreate($this, $values), $values,
            );
        };
    }
}
