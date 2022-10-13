<?php

namespace Laragear\Surreal\Query;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Processors\Processor;
use Illuminate\Support\Collection;
use RuntimeException;
use function array_keys;
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

    /**
     * Process an  "insert get ID" query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  string  $sql
     * @param  array  $values
     * @param  string|null  $sequence
     * @return int
     */
    public function processInsertGetId(Builder $query, $sql, $values, $sequence = null)
    {
        // Since an insert operation will return all values, we can return the IDs exclusively.
        // We can use the help of the SELECT processor to get the results we're interested in.
        $inserted = $this->processSelect($query, $query->getConnection()->insert($sql, $values));

        $id = data_get($inserted->first(), 'id');

        if (null === $id) {
            throw new RuntimeException('SurrealDB statement did not return an ID.');
        }

        // All the ids are `table:id` notation, which are strings.
        return $id;
    }

    /**
     * Process the results of a column listing query.
     *
     * @param  \Illuminate\Database\Eloquent\Collection|array  $results
     * @return array
     */
    public function processColumnListing($results)
    {
        return array_keys(data_get($results, 'result.fd'));
    }
}
