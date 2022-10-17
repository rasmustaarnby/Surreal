<?php

namespace Laragear\Surreal\Query\Concerns;

use Carbon\CarbonInterval;
use Illuminate\Database\Query\Builder;
use Laragear\Surreal\Query\ReturnType;
use function array_filter;
use function implode;

trait CompileQueryFlags
{
    /**
     * The return to specify for the operation.
     *
     * @var \Laragear\Surreal\Query\ReturnType|string[]
     */
    public ReturnType|array $return = ReturnType::Default;

    /**
     * Sets a timeout to the query.
     *
     * @var \Carbon\CarbonInterval|null
     */
    public ?CarbonInterval $timeout = null;

    /**
     * Sets if the FETCH operations should be parallel.
     *
     * @var bool
     */
    public bool $parallel = false;

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
        return match ($this->return) {
            ReturnType::Default => '',
            ReturnType::None => 'RETURN NONE',
            ReturnType::Diff => 'RETURN DIFF',
            ReturnType::Before => 'RETURN BEFORE',
            ReturnType::After => 'RETURN AFTER',
            default => 'RETURN '. $this->columnize($this->return),
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
        if ($this->timeout && $this->timeout->totalMicroseconds > 0) {
            return 'TIMEOUT ' . $this->getFormattedInterval($this->timeout);
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
        if ($this->parallel) {
            return 'PARALLEL';
        }
    }
}
