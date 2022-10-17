<?php

namespace Laragear\Surreal\Query\Concerns;

use Illuminate\Database\Query\Builder;

trait FetchRelations
{
    /**
     * The attribute keys to retrieve as parent records.
     *
     * @var array
     */
    public array $fetch = [];

    /**
     * Compile the FETCH relation operation.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string|void
     */
    public function compileFetch(Builder $query)
    {
        if (!empty($this->fetch)) {
            return 'FETCH '.$this->columnize($this->fetch);
        }
    }
}
