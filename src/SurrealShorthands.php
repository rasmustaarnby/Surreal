<?php

namespace Laragear\Surreal;

use Closure;
use Illuminate\Support\Str;
use RuntimeException;
use function substr_count;

trait SurrealShorthands
{
    /**
     * Begin a fluent query against a database table.
     *
     * @param  \Closure|\Illuminate\Database\Query\Builder|string  $table
     * @param  string|null  $as
     * @return \Illuminate\Database\Query\Builder
     */
    public function from($table, $as = null)
    {
        return $this->table($table, $as);
    }

    /**
     * Begin a fluent query against a database record by its ID.
     *
     * @param  string  $tableOrId
     * @param  \Stringable|string|int  $id
     * @return \Illuminate\Database\Query\Builder
     */
    public function id($tableOrId, $id = null)
    {
        // If the ID is added separately, unify the table and the ID itself.
        $tableOrId = $id
            ? Str::finish($tableOrId, ':').$id
            : (string) $tableOrId;

        // The record ID should have only one separator.
        if (substr_count($tableOrId, ':') !== 1) {
            throw new RuntimeException("The [$tableOrId] is not a valid SurrealDB record ID. Should be [table:id].");
        }

        return $this->table($tableOrId);
    }

    /**
     * Returns a record by its ID.
     *
     * @param  string  $id
     * @param  array|string  $columns
     * @return array|null
     */
    public function find($id, $columns = ['*'])
    {
        return $this->table(Str::before(':', $id))->find($id, $columns);
    }

    /**
     * Execute a query for a single record by ID or call a callback.
     *
     * @param  mixed  $id
     * @param  \Closure|array|string  $columns
     * @param  \Closure|null  $callback
     * @return mixed|static
     */
    public function findOr($id, $columns = ['*'], Closure $callback = null)
    {
        return $this->table(Str::before(':', $id))->findOr($id, $columns, $callback);
    }

    /**
     * Run a CREATE statement against the database.
     *
     * @param  string  $query
     * @param  array  $bindings
     * @return \Illuminate\Support\Collection
     */
    public function create($query, $bindings = [])
    {
        return $this->statement($query, $bindings);
    }
}
