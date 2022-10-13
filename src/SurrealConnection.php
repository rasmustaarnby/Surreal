<?php

namespace Laragear\Surreal;

use Closure;
use DateInterval;
use DateTimeInterface;
use Exception;
use Illuminate\Database\Connection;
use Illuminate\Database\QueryException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Laragear\Surreal\JsonRpc\ClientMessage;
use RuntimeException;
use function microtime;

class SurrealConnection extends Connection
{
    use DetectsConcurrencyErrors,
        DetectsLostConnections,
        SurrealShorthands;

    /**
     * The SurrealDB Client.
     *
     * @var \Laragear\Surreal\Contracts\SurrealClient
     */
    protected Contracts\SurrealClient $client;

    /**
     * Create a new database connection instance.
     *
     * @param  \Laragear\Surreal\Contracts\SurrealClient  $client
     * @param  string  $database
     * @param  string  $tablePrefix
     * @param  array  $config
     */
    public function __construct(Contracts\SurrealClient $client, $database = '', $tablePrefix = '', array $config = [])
    {
        parent::__construct(static function (): never {
            throw new RuntimeException('Connection asking for PDO, but we do not use PDO.');
        }, $database, $tablePrefix, $config);

        $this->setClient($client);
    }

    /**
     * Returns the HTTP/WS Client for SurrealDB.
     *
     * @return \Laragear\Surreal\Contracts\SurrealClient
     */
    public function getClient(): Contracts\SurrealClient
    {
        return $this->client;
    }

    /**
     * Sets the HTTP/WS Client for SurrealDB.
     *
     * @param  \Laragear\Surreal\Contracts\SurrealClient  $client
     * @return $this
     */
    public function setClient(Contracts\SurrealClient $client): static
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get the default query grammar instance.
     *
     * @return \Illuminate\Database\Query\Grammars\Grammar
     */
    protected function getDefaultQueryGrammar()
    {
        return new Query\SurrealGrammar();
    }

    /**
     * Get the default post processor instance.
     *
     * @return \Illuminate\Database\Query\Processors\Processor
     */
    protected function getDefaultPostProcessor()
    {
        return new Query\SurrealProcessor();
    }

    /**
     * Get a schema builder instance for the connection.
     *
     * @return \Illuminate\Database\Schema\Builder
     */
    public function getSchemaBuilder()
    {
        if (null === $this->schemaGrammar) {
            $this->useDefaultSchemaGrammar();
        }

        return new Schema\SurrealSchemaBuilder($this);
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return \Illuminate\Database\Schema\Grammars\Grammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return new Schema\SurrealSchemaGrammar();
    }

    /**
     * Get the schema state for the connection.
     *
     * @param  \Illuminate\Filesystem\Filesystem|null  $files
     * @param  callable|null  $processFactory
     * @return \Illuminate\Database\Schema\SchemaState
     */
    public function getSchemaState(Filesystem $files = null, callable $processFactory = null)
    {
        return new Schema\SurrealSchemaState($this, $files, $processFactory);
    }

    /**
     * Run a select statement against the database.
     *
     * @param  string  $query
     * @param  array  $bindings
     * @param  bool  $useReadPdo
     * @return \Illuminate\Support\Collection
     */
    public function select($query, $bindings = [], $useReadPdo = true)
    {
        return $this->statement($query, $bindings);
    }

    /**
     * Execute an SQL statement and return the boolean result.
     *
     * @param  string  $query
     * @param  array  $bindings
     * @return \Illuminate\Support\Collection
     */
    public function statement($query, $bindings = [])
    {
        return $this->run($query, $bindings, function ($query, $bindings) {
            if ($this->pretending()) {
                return new Collection();
            }

            return $this->client->send(
                $this->prepared(ClientMessage::queryWithUlid($query, $this->prepareBindings($bindings)))
            );
        });
    }

    /**
     * Run a select statement against the database and returns a generator.
     *
     * @param  string  $query
     * @param  array  $bindings
     * @param  bool  $useReadPdo
     * @return \Generator
     */
    public function cursor($query, $bindings = [], $useReadPdo = true)
    {
        $results = $this->run($query, $bindings, function ($query, $bindings) {
            if ($this->pretending()) {
                return [];
            }

            // Using a cursor we can make an async query that returns each item.
            return $this->client->send(
                $this->prepared(ClientMessage::queryWithUlid($query, $this->prepareBindings($bindings))), true
            );
        });

        foreach ($results as $result) {
            yield $result;
        }
    }

    /**
     * Configure the PDO prepared statement.
     *
     * @param  \Laragear\Surreal\JsonRpc\ClientMessage  $statement
     * @return \Laragear\Surreal\JsonRpc\ClientMessage
     */
    protected function prepared($statement)
    {
        $this->event(new Events\StatementPrepared($this, $statement));

        return $statement;
    }

    /**
     * Run an SQL statement and get the number of rows affected.
     *
     * @param  string  $query
     * @param  array  $bindings
     * @return int
     */
    public function affectingStatement($query, $bindings = [])
    {
        return $this->run($query, $bindings, function ($query, $bindings) {
            if ($this->pretending()) {
                return 0;
            }

            $affected = $this->client->send(
                $this->prepared(ClientMessage::queryWithUlid($query, $this->prepareBindings($bindings)))
            )->count();

            $this->recordsHaveBeenModified((bool) $affected);

            return $affected;
        });
    }

    /**
     * Run a raw, unprepared query against the database.
     *
     * @param  string  $query
     * @return \Illuminate\Support\Collection
     */
    public function unprepared($query)
    {
        return $this->run($query, [], function ($query) {
            if ($this->pretending()) {
                return new Collection();
            }

            $result = $this->client->send(
                $this->prepared(ClientMessage::queryWithUlid($query, []))
            );

            $this->recordsHaveBeenModified($result->isNotEmpty());

            return $result;
        });
    }

    /**
     * Prepare the query bindings for execution.
     *
     * @param  array  $bindings
     * @return array
     */
    public function prepareBindings(array $bindings)
    {
        $grammar = $this->getQueryGrammar();

        foreach ($bindings as $key => $value) {
            $bindings[$key] = match (true) {
                $value instanceof DateTimeInterface => $value->format($grammar->getDateFormat()),
                $value instanceof DateInterval => $grammar->getFormattedInterval($value),
                default => $value,
            };
        }

        return $bindings;
    }

    /**
     * Run a SQL statement and log its execution context.
     *
     * @param  string  $query
     * @param  array  $bindings
     * @param  \Closure  $callback
     * @param  bool  $async
     * @return mixed
     */
    protected function run($query, $bindings, Closure $callback, $async = false)
    {
        foreach ($this->beforeExecutingCallbacks as $beforeExecutingCallback) {
            $beforeExecutingCallback($query, $bindings, $this);
        }

        $start = microtime(true);

        // Here we will run this query. If an exception occurs we'll determine if it was
        // caused by a connection that has been lost. If that is the cause, we'll try
        // to re-establish connection and re-run the query with a fresh connection.
        try {
            $result = $this->runQueryCallback($query, $bindings, $callback);
        } catch (QueryException $e) {
            $result = $this->handleQueryException(
                $e, $query, $bindings, $callback
            );
        }

        // Once we have run the query we will calculate the time that it took to run and
        // then log the query, bindings, and execution time so we will report them on
        // the event that the developer needs them. We'll log time in milliseconds.
        $this->logQuery(
            $query, $bindings, $this->getElapsedTime($start)
        );

        return $result;
    }

    /**
     * Run a SQL statement.
     *
     * @param  string  $query
     * @param  array  $bindings
     * @param  \Closure  $callback
     * @return mixed
     *
     * @throws \Illuminate\Database\QueryException
     */
    protected function runQueryCallback($query, $bindings, Closure $callback)
    {
        try {
            return $callback($query, $bindings);
        } catch (Exception $e) {
            throw new Exceptions\QueryException($query, $this->prepareBindings($bindings), $e);
        }
    }

    /**
     * Reconnect to the database if a PDO connection is missing.
     *
     * @return void
     */
    protected function reconnectIfMissingConnection()
    {
        if (! $this->client->isRunning()) {
            $this->reconnect();
        }
    }
}
