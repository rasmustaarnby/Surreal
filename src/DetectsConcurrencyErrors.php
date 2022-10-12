<?php

namespace Laragear\Surreal;

use Illuminate\Support\Str;
use Throwable;

trait DetectsConcurrencyErrors
{
    /**
     * Determine if the given exception was caused by a concurrency error such as a deadlock or serialization failure.
     *
     * @param  \Throwable  $e
     * @return bool
     */
    protected function causedByConcurrencyError(Throwable $e)
    {
        return Str::contains($e->getMessage(), 'Failed to resolve lock');
    }
}