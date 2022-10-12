<?php

namespace Laragear\Surreal\JsonRpc;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Str;
use JsonSerializable;
use Laragear\Surreal\Query\SurrealGrammar;
use Stringable;
use function array_map;
use function json_encode;

class Statement implements Stringable, JsonSerializable, Jsonable
{
    /**
     * Create a new statement instance.
     *
     * @param  string  $statement
     * @param  array{string}  $bindingKeys
     */
    public function __construct(readonly public string $statement, readonly public array $bindingKeys)
    {
        //
    }

    /**
     * Return a string representation of the object.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @return string
     */
    public function jsonSerialize(): string
    {
        return self::setVariables($this->statement, $this->bindingKeys);
    }

    /**
     * Replaces all the query bindings for the placeholders.
     *
     * @param  string  $statement
     * @param  array  $keys
     * @return string
     */
    protected static function setVariables(string $statement, array $keys): string
    {
        return Str::replaceArray(SurrealGrammar::BINDING_STRING, array_map(static function (int|string $key): string {
            return '$' . $key;
        }, $keys), $statement);
    }
}