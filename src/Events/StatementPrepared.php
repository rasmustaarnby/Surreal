<?php

namespace Laragear\Surreal\Events;

use Laragear\Surreal\JsonRpc\ClientMessage;
use Laragear\Surreal\SurrealConnection;

class StatementPrepared
{
    /**
     * Create a new event instance.
     *
     * @param  \Laragear\Surreal\SurrealConnection  $connection
     * @param  \Laragear\Surreal\JsonRpc\ClientMessage  $statement
     */
    public function __construct(public SurrealConnection $connection, public ClientMessage $statement)
    {
        //
    }
}
