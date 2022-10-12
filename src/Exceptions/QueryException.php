<?php

namespace Laragear\Surreal\Exceptions;

use Illuminate\Database\QueryException as BaseQueryException;
use Illuminate\Support\Str;
use Laragear\Surreal\Query\SurrealGrammar;
use Throwable;

class QueryException extends BaseQueryException
{
    /**
     * Format the SQL error message.
     *
     * @param  string  $sql
     * @param  array  $bindings
     * @param  \Throwable  $previous
     * @return string
     */
    protected function formatMessage($sql, $bindings, Throwable $previous)
    {
        // We need to use the grammar placeholder.
        return $previous->getMessage().' (SQL: '.Str::replaceArray(SurrealGrammar::BINDING_STRING, $bindings, $sql).')';
    }
}