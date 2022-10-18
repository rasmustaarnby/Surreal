<?php

namespace Laragear\Surreal\Query\Concerns;

use Illuminate\Database\Query\Builder;
use Laragear\Surreal\Query\ReturnType;
use function array_filter;
use function implode;

trait CompileQueryFlags
{
    /**
     * Compiles all flags into SurrealSQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string
     */
    protected function compileFlags(Builder $query)
    {
        return implode(' ', array_filter([
            $this->compileReturn($query),
            $this->compileTimeout($query),
            $this->compileParallel($query),
        ]));
    }

    /**
     * Compiles all flags, except the return data, into SurrealSQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string
     */
    protected function compileFlagsWithoutReturn(Builder $query)
    {
        return implode(' ', array_filter([
            $this->compileTimeout($query),
            $this->compileParallel($query),
        ]));
    }

    /**
     * Compiles a return statement.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string
     */
    protected function compileReturn(Builder $query)
    {
        return match ($query->joins['return'] ?? ReturnType::Default) {
            ReturnType::Default => '',
            ReturnType::None => 'RETURN NONE',
            ReturnType::Diff => 'RETURN DIFF',
            ReturnType::Before => 'RETURN BEFORE',
            ReturnType::After => 'RETURN AFTER',
            default => 'RETURN '. $this->columnize($query->joins['return']),
        };
    }

    /**
     * Sets the timeout flag for the statement.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string|void
     */
    protected function compileTimeout(Builder $query)
    {
        if (isset($query->joins['timeout']) && $query->joins['timeout']->totalMicroseconds > 0) {
            return 'TIMEOUT ' . $this->getFormattedInterval($query->joins['timeout']);
        }
    }

    /**
     * Sets the parallel FETCH on the statement.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string|void
     */
    protected function compileParallel(Builder $query)
    {
        if ($query->joins['parallel'] ?? false) {
            return 'PARALLEL';
        }
    }
}
