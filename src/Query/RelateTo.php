<?php

namespace Laragear\Surreal\Query;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;
use Laragear\Surreal\Surreal;
use RuntimeException;
use function is_array;
use function ksort;
use function reset;

class RelateTo
{
    /**
     * Create a new Relate To instance.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  string  $relatedId
     */
    public function __construct(protected Builder $query, protected string $relatedId)
    {
        if (Surreal::isNotId($query->from)) {
            throw new RuntimeException("The [$query->from] is not a valid SurrealDB record ID. Should be [table:id].");
        }

        if (Surreal::isNotId($relatedId)) {
            throw new RuntimeException("The [$relatedId] is not a valid SurrealDB record ID. Should be [table:id].");
        }
    }

    /**
     * Sets the Graph Edge table to create the relation.
     *
     * @param  string  $edge
     * @param  array  $data
     * @return \Illuminate\Support\Collection|null
     */
    public function through($edge, array $data = [])
    {
        if (is_array(reset($data))) {
            ksort($data);
        }

        $this->query->applyBeforeQueryCallbacks();

        return $this->query->connection->relate(
            $this->query->grammar->compileRelate($this->query, $edge, $this->relatedId, $data), $data,
        );
    }

    /**
     * Handle dynamic calls to the object.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return \Illuminate\Support\Collection|null
     */
    public function __call(string $method, array $parameters)
    {
        return $this->through(Str::snake($method), ...$parameters);
    }
}
