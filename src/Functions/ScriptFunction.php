<?php

namespace Laragear\Surreal\Functions;

use Illuminate\Contracts\Support\Arrayable;
use Stringable;
use function str_contains;
use const PHP_EOL;

class ScriptFunction
{
    /**
     * Create a new Script function instance.
     *
     * @param  \Stringable|string  $script
     * @param  \Illuminate\Contracts\Support\Arrayable|array  $arguments
     */
    public function __construct(protected Stringable|string $script, protected Arrayable|array $arguments)
    {
        //
    }

    public function withArgs(...$args): static
    {
        $this->arguments = $args;
    }
}