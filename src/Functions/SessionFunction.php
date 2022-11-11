<?php

namespace Laragear\Surreal\Functions;

class SessionFunction
{
    /**
     * Returns the currently selected database.
     *
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function db(): SurrealFunction
    {
        return SurrealFunction::make("session::db()");
    }

    /**
     * Returns the currently selected database.
     *
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function database(): SurrealFunction
    {
        return $this->db();
    }

    /**
     * Returns the current user's session ID.
     *
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function id(): SurrealFunction
    {
        return SurrealFunction::make("session::id()");
    }

    /**
     * Returns the current user's session IP address.
     *
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function ip(): SurrealFunction
    {
        return SurrealFunction::make("session::ip()");
    }

    /**
     * Returns the currently selected namespace.
     *
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function ns(): SurrealFunction
    {
        return SurrealFunction::make("session::ns()");
    }

    /**
     * Returns the currently selected namespace.
     *
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function namespace(): SurrealFunction
    {
        return $this->ns();
    }

    /**
     * Returns the current user's HTTP origin.
     *
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function origin(): SurrealFunction
    {
        return SurrealFunction::make("session::origin()");
    }

    /**
     * Returns the current user's authentication scope.
     *
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function sc(): SurrealFunction
    {
        return SurrealFunction::make("session::sc()");
    }

    /**
     * Returns the current user's authentication scope.
     *
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function scope(): SurrealFunction
    {
        return $this->sc();
    }
}
