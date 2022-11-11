<?php

namespace Laragear\Surreal\Functions;

class ParseFunction
{
    /**
     * Parses and returns an email domain from an email address.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function emailDomain(string $value): SurrealFunction
    {
        return SurrealFunction::make('parse::email::domain($?)', [$value]);
    }

    /**
     * Parses and returns an email username from an email address.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function emailUser(string $value): SurrealFunction
    {
        return SurrealFunction::make('parse::email::user($?)', [$value]);
    }

    /**
     * Parses and returns the domain from a URL.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function urlDomain(string $value): SurrealFunction
    {
        return SurrealFunction::make('parse::url::domain($?)', [$value]);
    }

    /**
     * Parses and returns the fragment from a URL.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function urlFragment(string $value): SurrealFunction
    {
        return SurrealFunction::make('parse::url::fragment($?)', [$value]);
    }

    /**
     * Parses and returns the hostname from a URL.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function urlHost(string $value): SurrealFunction
    {
        return SurrealFunction::make('parse::url::host($?)', [$value]);
    }

    /**
     * Parses and returns the path from a URL.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function urlPath(string $value): SurrealFunction
    {
        return SurrealFunction::make('parse::url::path($?)', [$value]);
    }

    /**
     * Parses and returns the port number from a URL.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function urlPort(string $value): SurrealFunction
    {
        return SurrealFunction::make('parse::url::port($?)', [$value]);
    }

    /**
     * Parses and returns the query string from a URL.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function urlQuery(string $value): SurrealFunction
    {
        return SurrealFunction::make('parse::url::query($?)', [$value]);
    }
}
