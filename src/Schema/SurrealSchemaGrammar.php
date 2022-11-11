<?php

namespace Laragear\Surreal\Schema;

use Illuminate\Database\Connection;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\Grammar;
use Illuminate\Support\Fluent;
use Laragear\Surreal\Query\SurrealGrammar;
use RuntimeException;

class SurrealSchemaGrammar extends Grammar
{
    use SurrealSchemaTypes;
    use SurrealCustomTypes;

    /**
     * The possible column modifiers.
     *
     * @var string[]
     */
    protected $modifiers = ['Default', 'Increment'];

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

    /**
     * Compile a create database command.
     *
     * @param  string  $name
     * @param  \Illuminate\Database\Connection  $connection
     * @return string
     */
    public function compileCreateDatabase($name, $connection)
    {
        return "DEFINE DATABASE {$this->wrapValue($name)}";
    }

    /**
     * Compile a drop database if exists command.
     *
     * @param  string  $name
     * @return string
     */
    public function compileDropDatabaseIfExists($name)
    {
        return "REMOVE DATABASE {$this->wrapValue($name)}";
    }

    /**
     * Compile the query to determine the list of tables.
     *
     * @return string
     */
    public function compileTableExists()
    {
        return 'INFO FOR TABLE '.SurrealGrammar::BINDING_STRING;
    }

    /**
     * Compile a create table command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @return string[]
     */
    public function compileCreate(Blueprint $blueprint)
    {
        return [
            'DEFINE TABLE '.$this->wrapTable($blueprint).' SCHEMAFULL',
            ...$this->getColumns($blueprint),
            ...$this->addForeignKeys($blueprint),
            ...$this->addPrimaryKeys($blueprint),
        ];
    }

    /**
     * Compile the blueprint's column definitions.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @return array
     */
    protected function getColumns(Blueprint $blueprint)
    {
        $columns = [];

        $table = $this->wrapTable($blueprint);

        foreach ($blueprint->getAddedColumns() as $column) {
            // Each of the column types has their own compiler functions, which are tasked
            // with turning the column definition into its SQL format for this platform
            // used by the connection. The column's modifiers are compiled and added.
            $columns[] = $this->addModifiers(
                "DEFINE FIELD {$this->wrap($column)} ON $table TYPE {$this->getType($column)}", $blueprint, $column
            );
        }

        return $columns;
    }

    /**
     * Get the SQL for a default column modifier.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $column
     * @return string|void
     */
    protected function modifyDefault(Blueprint $blueprint, Fluent $column)
    {
        if ($column->default) {
            return ' VALUE '.$this->getDefaultValue($column->default);
        }
    }

    /**
     * Get the foreign key syntax for a table creation statement.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @return string[]
     */
    protected function addForeignKeys(Blueprint $blueprint)
    {
        return [];
    }

    /**
     * Get the primary key syntax for a table creation statement.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @return string[]
     */
    protected function addPrimaryKeys(Blueprint $blueprint)
    {
        if ($this->getCommandByName($blueprint, 'primary')) {
            throw new RuntimeException('The database driver does not support alternative primary keys.');
        }

        return [];
    }

    /**
     * Compile alter table commands for adding columns.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @return array
     */
    public function compileAdd(Blueprint $blueprint)
    {
        return $this->getColumns($blueprint);
    }

    /**
     * Compile a drop table command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @return string
     */
    public function compileDrop(Blueprint $blueprint)
    {
        return "REMOVE TABLE {$this->wrapTable($blueprint)}";
    }

    /**
     * Compile a drop table (if exists) command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @return string
     */
    public function compileDropIfExists(Blueprint $blueprint)
    {
        return $this->compileDrop($blueprint);
    }

    /**
     * Compile a drop index command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileDropIndex(Blueprint $blueprint, Fluent $command)
    {
        return "REMOVE INDEX {$this->wrap($command->index)} ON TABLE {$this->wrapTable($blueprint)}";
    }

    /**
     * Compile a drop unique key command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileDropUnique(Blueprint $blueprint, Fluent $command)
    {
        return $this->compileDropIndex($blueprint, $command);
    }

    /**
     * Compile a drop column command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @param  \Illuminate\Database\Connection  $connection
     * @return array
     */
    public function compileDropColumn(Blueprint $blueprint, Fluent $command)
    {
        $table = $this->wrapTable($blueprint);

        $columns = [];

        foreach ($command->columns as $column) {
            $columns[] = "REMOVE FIELD {$this->wrap($column)} ON TABLE $table";
        }

        return $columns;
    }

    /**
     * Compile a rename table command.
     *
     * @return never
     */
    public function compileRename()
    {
        throw new RuntimeException('The database driver does not support renaming tables.');
    }

    /**
     * Compile a rename index command.
     *
     * @return never
     */
    public function compileRenameIndex()
    {
        throw new RuntimeException('The database driver does not support renaming indexes.');
    }

    /**
     * Compile a rename column command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @param  \Illuminate\Database\Connection  $connection
     * @return array
     */
    public function compileRenameColumn(Blueprint $blueprint, Fluent $command, Connection $connection)
    {
        throw new RuntimeException('The database driver does not support renaming columns/fields.');
    }

    /**
     * Compile a foreign key command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileForeign(Blueprint $blueprint, Fluent $command)
    {
        throw new RuntimeException('The database driver does not support foreign fields.');
    }

    /**
     * Compile a plain index key command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileIndex(Blueprint $blueprint, Fluent $command)
    {
        return sprintf('DEFINE INDEX %s ON TABLE %s FIELDS %s',
            $this->wrap($command->index),
            $this->wrapTable($blueprint),
            $this->columnize($command->columns)
        );
    }

    /**
     * Compile a unique key command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileUnique(Blueprint $blueprint, Fluent $command)
    {
        return $this->compileIndex($blueprint, $command).' UNIQUE';
    }

    /**
     * Get the SQL for an auto-increment column modifier.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $column
     * @return never
     */
    protected function modifyIncrement(Blueprint $blueprint, Fluent $column)
    {
        if ($column->autoIncrement) {
            throw new RuntimeException('The database driver does not support incrementing fields.');
        }
    }

    /**
     * Format a value so that it can be used in "default" clauses.
     *
     * @param  mixed  $value
     * @return string
     */
    protected function getDefaultValue($value)
    {
        if ($value instanceof Expression) {
            return $value;
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return "\"".$value."\"";
    }
}
