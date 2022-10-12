<?php

namespace Laragear\Surreal\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;
use Laragear\Surreal\Contracts\SurrealException;

class FailedResponseError extends Exception implements SurrealException
{
    //
}