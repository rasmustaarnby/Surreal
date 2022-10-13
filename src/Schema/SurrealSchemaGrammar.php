<?php

namespace Laragear\Surreal\Schema;

use Illuminate\Database\Schema\Grammars\Grammar;

class SurrealSchemaGrammar extends Grammar
{
    /**
     * Compile the query to determine the list of columns.
     *
     * @param  string  $table
     * @return string
     */
    public function compileColumnListing(string $table): string
    {
        return "INFO FOR TABLE $table";
    }
}
