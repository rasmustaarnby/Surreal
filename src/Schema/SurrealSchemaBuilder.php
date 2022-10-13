<?php

namespace Laragear\Surreal\Schema;

use Illuminate\Database\Schema\Builder;

/**
 * @property-read \Laragear\Surreal\SurrealConnection $connection
 */
class SurrealSchemaBuilder extends Builder
{
    /**
     * Get the column listing for a given table.
     *
     * @param  string  $table
     * @return array
     */
    public function getColumnListing($table)
    {
        $table = $this->connection->getTablePrefix().$table;

        $results = $this->connection->statement($this->grammar->compileColumnListing($table));

        return $this->connection->getPostProcessor()->processColumnListing($results);
    }
}
