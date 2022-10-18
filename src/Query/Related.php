<?php

namespace Laragear\Surreal\Query;

use Illuminate\Contracts\Database\Query\Builder as BuilderContract;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;
use function array_key_last;
use function substr;

/**
 * @mixin \Illuminate\Database\Query\Builder
 */
class Related implements BuilderContract
{
    /**
     * The key to use set the chain in the joins array.
     *
     * @var int
     */
    protected int $key = 0;

    /**
     * Create a new Related instance.
     *
     * @param  \Illuminate\Database\Query\Builder  $builder
     * @param  array  $chain
     */
    public function __construct(protected Builder $builder, protected array $chain = [])
    {
        if (isset($this->builder->joins['edges'])) {
            $this->key = array_key_last($this->builder->joins['edges']);
        }
    }

    /**
     * Travel to a graph edge.
     *
     * @param  string  $relation
     * @param  (\Closure(\Illuminate\Database\Query\Builder):void)|null $callback
     * @return $this
     */
    public function to($relation, $callback = null)
    {
        $this->builder->joins['edges'][$this->key][Str::start($relation, '->')] = $callback;

        return $this;
    }

    /**
     * Travel from a graph edge.
     *
     * @param  string  $relation
     * @param  (\Closure(\Illuminate\Database\Query\Builder):void)|null $callback
     * @return $this
     */
    public function from($relation, $callback = null)
    {
        $this->builder->joins['edges'][$this->key][Str::start($relation, '<-')] = $callback;

        return $this;
    }

    /**
     * Handle dynamic calls to the object attributes.
     *
     * @param  string  $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->__call($name, []);
    }

    /**
     * Handle dynamic calls to the object methods.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (Str::startsWith($method, 'to')) {
            return $this->to(Str::snake(substr($method, 2)), ...$parameters);
        }

        if (Str::startsWith($method, 'from')) {
            return $this->to(Str::snake(substr($method, 4)), ...$parameters);
        }

        return $this->builder->{$method}(...$parameters);
    }
}
