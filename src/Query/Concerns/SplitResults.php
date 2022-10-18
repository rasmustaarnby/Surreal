<?php

namespace Laragear\Surreal\Query\Concerns;

use Illuminate\Database\Query\Builder;

trait SplitResults
{
    /**
     * Compiles a SPLIT flag.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string|void
     */
    public function compileSplit(Builder $query)
    {
        if (!empty($query->joins['split'])) {
            return 'SPLIT AT ' . $this->columnize((array) $query->joins['split']);
        }
    }
}
