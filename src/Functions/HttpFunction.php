<?php

namespace Laragear\Surreal\Functions;

use function json_encode;

class HttpFunction
{
    /**
     * Perform a remote HTTP HEAD request.
     *
     * @param  string  $url
     * @param  array  $headers
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function head(string $url, array $headers = []): SurrealFunction
    {
        $function = $headers
            ? 'http::head($?, $?)'
            : 'http::head($?)';

        return SurrealFunction::make($function, [$url, json_encode($headers)]);
    }

    /**
     * Perform a remote HTTP GET request.
     *
     * @param  string  $url
     * @param  array  $headers
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function get(string $url, array $headers = []): SurrealFunction
    {
        $function = $headers
            ? 'http::get($?, $?)'
            : 'http::get($?)';

        return SurrealFunction::make($function, [$url, json_encode($headers)]);
    }

    /**
     * Perform a remote HTTP PUT request.
     *
     * @param  string  $url
     * @param  array  $data
     * @param  array  $headers
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function put(string $url, array $data, array $headers = []): SurrealFunction
    {
        $function = $headers
            ? 'http::put($?, $?, $?)'
            : 'http::put($?, $?)';

        return SurrealFunction::make($function, [$url, json_encode($data), json_encode($headers)]);
    }

    /**
     * Perform a remote HTTP POST request.
     *
     * @param  string  $url
     * @param  array  $data
     * @param  array  $headers
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function post(string $url, array $data, array $headers = []): SurrealFunction
    {
        $function = $headers
            ? 'http::post($?, $?, $?)'
            : 'http::post($?, $?)';

        return SurrealFunction::make($function, [$url, json_encode($data), json_encode($headers)]);
    }

    /**
     * Perform a remote HTTP PATCH request.
     *
     * @param  string  $url
     * @param  array  $data
     * @param  array  $headers
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function patch(string $url, array $data, array $headers = []): SurrealFunction
    {
        $function = $headers
            ? 'http::patch($?, $?, $?)'
            : 'http::patch($?, $?)';

        return SurrealFunction::make($function, [$url, json_encode($data), json_encode($headers)]);
    }

    /**
     * Perform a remote HTTP DELETE request.
     *
     * @param  string  $url
     * @param  array  $headers
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function delete(string $url, array $headers = []): SurrealFunction
    {
        $function = $headers
            ? 'http::delete($?, $?)'
            : 'http::delete($?)';

        return SurrealFunction::make($function, [$url, json_encode($headers)]);
    }
}
