<?php

namespace Laragear\Surreal\Events;

use Laragear\Surreal\SurrealConnection;
use Laragear\Surreal\JsonRpc\QueryMessage;

class StatementPrepared
{
    /**
     * Create a new event instance.
     *
     * @param  \Laragear\Surreal\SurrealConnection  $connection
     * @param  \Laragear\Surreal\JsonRpc\QueryMessage  $statement
     */
    public function __construct(public SurrealConnection $connection, public QueryMessage $statement)
    {
        //
    }
}