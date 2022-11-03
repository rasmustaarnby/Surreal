<?php

namespace Laragear\Surreal\Schema;

use Illuminate\Database\Schema\Builder;

/**
 * @property-read \Laragear\Surreal\SurrealConnection $connection
 * @property-read \Laragear\Surreal\Schema\SurrealSchemaGrammar $grammar
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

    /**
     * Create a database in the schema.
     *
     * @param  string  $name
     * @return bool
     */
    public function createDatabase($name)
    {
        return (bool) $this->connection->statement(
            $this->grammar->compileCreateDatabase($name, $this->connection)
        );
    }

    /**
     * Drop a database from the schema if the database exists.
     *
     * @param  string  $name
     * @return bool
     */
    public function dropDatabaseIfExists($name)
    {
        return (bool) $this->connection->statement(
            $this->grammar->compileDropDatabaseIfExists($name)
        );
    }
}
