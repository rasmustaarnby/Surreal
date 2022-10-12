<?php

namespace Laragear\Surreal;

use Illuminate\Support\Str;
use Throwable;

trait DetectsLostConnections
{
    /**
     * Determine if the given exception was caused by a lost connection.
     *
     * @param  \Throwable  $e
     * @return bool
     */
    protected function causedByLostConnection(Throwable $e)
    {
        return $e instanceof Exceptions\NotConnectedException;
    }
}