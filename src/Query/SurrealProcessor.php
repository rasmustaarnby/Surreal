<?php

namespace Laragear\Surreal\Query;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Processors\Processor;

class SurrealProcessor extends Processor
{
    /**
     * Process the results of a "select" query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $results
     * @return array
     */
    public function processSelect(Builder $query, $results)
    {
        return $results;
    }
}