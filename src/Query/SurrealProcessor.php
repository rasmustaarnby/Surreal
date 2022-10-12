<?php

namespace Laragear\Surreal\Query;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Processors\Processor;
use Illuminate\Support\Collection;
use function data_get;

class SurrealProcessor extends Processor
{
    /**
     * Process the results of a "select" query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  \Illuminate\Support\Collection  $results
     * @return \Illuminate\Support\Collection
     */
    public function processSelect(Builder $query, $results)
    {
        // If the results are an array of one item, we can just return that.
        if ($results->containsOneItem()) {
            return new Collection(data_get($results, '0.result'));
        }

        // If not, we will return them all by mapping them.
        return $results->map(static function (array $response): array {
            return $response['result'];
        })->flatten(1);
    }
}
