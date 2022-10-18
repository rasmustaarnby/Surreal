<?php

namespace Laragear\Surreal\Query\Concerns;

use Illuminate\Database\Query\Builder;

trait FetchRelations
{
    /**
     * Compile the FETCH relation operation.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string|void
     */
    public function compileFetch(Builder $query)
    {
        if (!empty($query->joins['fetch'])) {
            return 'FETCH '.$this->columnize((array) $query->joins['fetch']);
        }
    }
}
