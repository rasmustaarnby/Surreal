<?php

namespace Laragear\Surreal\Functions;

class ScriptFunction
{
    /**
     * Create a new Script function instance.
     *
     * @param  string  $script
     */
    public function __construct(protected string $script)
    {
        //
    }

    /**
     * Returns the function as a SurrealSQL string.
     *
     * @return string
     */
    public function toSql(): string
    {
        return <<<SCRIPT
function () {
    $this->script
}
SCRIPT
            ;

    }
}
