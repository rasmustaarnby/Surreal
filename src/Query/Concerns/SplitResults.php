<?php

namespace Laragear\Surreal\Query\Concerns;

use Illuminate\Database\Query\Builder;

trait SplitResults
{
    /**
     * The keys of the record to split the results.
     *
     * @var array
     */
    public array $split = [];

    /**
     * Compiles a SPLIT flag.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string|void
     */
    public function compileSplit(Builder $query)
    {
        if ($this->split) {
            return 'SPLIT AT ' . $this->columnize($this->split);
        }
    }
}
