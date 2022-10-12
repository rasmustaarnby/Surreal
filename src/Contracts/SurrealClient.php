<?php

namespace Laragear\Surreal\Contracts;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Laragear\Surreal\JsonRpc\QueryMessage;

interface SurrealClient
{
    /**
     * Sets the configuration for the SurrealDB Client.
     *
     * @param  array  $config
     * @return $this
     */
    public function configure(array $config): static;

    /**
     * Starts the client.
     *
     * @return void
     */
    public function start(): void;

    /**
     * Stops the client.
     *
     * @return void
     */
    public function stop(): void;

    /**
     * Check if the Client has started.
     *
     * @return bool
     */
    public function isRunning(): bool;

    /**
     * Sends a statement to SurrealDB, returns a Collection or a Lazy Collection if async.
     *
     * @param  \Laragear\Surreal\JsonRpc\QueryMessage  $statement
     * @param  bool  $async
     * @return \Illuminate\Support\Collection|\Illuminate\Support\LazyCollection
     * @throws \Laragear\Surreal\Exceptions\NotConnectedException
     */
    public function send(QueryMessage $statement, bool $async = false): Collection|LazyCollection;

    /**
     * Check if the Client instance is capturing statements for transactions.
     *
     * @return bool
     */
    public function isUnderTransaction(): bool;

    /**
     * Starts a new transaction and captures the next statements.
     *
     * @return void
     */
    public function beginTransaction(): void;

    /**
     * Commits the undergoing transaction.
     *
     * @return void
     */
    public function commit(): void;

    /**
     * Cancels the undergoing transaction and does nothing.
     *
     * @return void
     */
    public function cancel(): void;
}
