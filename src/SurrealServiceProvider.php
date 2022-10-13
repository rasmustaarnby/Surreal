<?php

namespace Laragear\Surreal;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\ServiceProvider;
use function class_exists;

class SurrealServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register(): void
    {
        $this->registerClient();
        $this->registerDatabaseDriver();
        $this->registerClientShutdown();
        $this->registerBuilderMacros();
        $this->registerBlueprintMacros();
    }

    /**
     * Register the SurrealDB Client.
     *
     * @return void
     */
    protected function registerClient(): void
    {
        $this->app->singleton('surreal.client', Tcp\WebsocketClient::make(...));
        $this->app->alias(Contracts\SurrealClient::class, 'surreal.client');
    }

    /**
     * Registers the SurrealDB database driver.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function registerDatabaseDriver(): void
    {
        $this->callAfterResolving('db', static function (DatabaseManager $manager, Container $app): void {
            $manager->extend('surreal', static function (array $config) use ($app): SurrealConnection {
                /** @var \Laragear\Surreal\Contracts\SurrealClient $client */
                $client = $app->make(Contracts\SurrealClient::class);

                $client->configure($config)->start();

                return new SurrealConnection($client, $config['database'], $config['prefix'] ?? '', $config);
            });
        });
    }

    /**
     * Registers a graceful shutdown of the client.
     *
     * @return void
     */
    protected function registerClientShutdown(): void
    {
        // On Laravel Octane, we will want to reuse the WS connection to Surreal DB to avoid
        // the connection overhead and disconnect from the DB each time the app terminates.
        // Otherwise, we will "hear" when the app terminates after it handles the request.
        if (class_exists('Laravel\Octane\Events\WorkerStopping')) {
            $this->callAfterResolving('events', static function (Dispatcher $event): void {
                $event->listen('Laravel\Octane\Events\WorkerStopping', static function (object $event): void {
                    if ($event->app->resolved('surreal.client')) {
                        $event->app->make('surreal.client')->stop();
                    }
                });
            });
        } else {
            $this->app->terminating(static function (Container $app): void {
                if ($app->resolved('surreal.client')) {
                    $app->make('surreal.client')->stop();
                }
            });
        }
    }

    /**
     * Registers the Query Builder macros.
     *
     * @return void
     */
    protected function registerBuilderMacros(): void
    {
        // TODO: Check the README for the macros to register.
    }

    /**
     * Registers the Blueprint macros.
     *
     * @return void
     */
    protected function registerBlueprintMacros(): void
    {
        // TODO: Check the README for the macros to register.
    }
}
