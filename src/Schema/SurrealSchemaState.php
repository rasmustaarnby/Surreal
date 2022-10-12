<?php

namespace Laragear\Surreal\Schema;

use Illuminate\Database\Connection;
use Illuminate\Database\Schema\SchemaState;

class SurrealSchemaState extends SchemaState
{

    /**
     * Dump the database's schema into a file.
     *
     * @param  \Illuminate\Database\Connection  $connection
     * @param  string  $path
     * @return void
     */
    public function dump(Connection $connection, $path)
    {
        // TODO: Implement dump() method.
    }

    /**
     * Load the given schema file into the database.
     *
     * @param  string  $path
     * @return void
     */
    public function load($path)
    {
        // TODO: Implement load() method.
    }
}