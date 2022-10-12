<?php

namespace Laragear\Surreal\JsonRpc;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use Stringable;
use function json_encode;

class QueryParameters implements Stringable, JsonSerializable, Jsonable, Arrayable
{
    /**
     * Create a new Query Parameters instance.
     *
     * @param  array  $parameters
     */
    public function __construct(readonly public array $parameters)
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
    public function toJson($options = 0)
    {
        return json_encode($this->parameters, $options);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->parameters;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return object
     */
    public function jsonSerialize(): object
    {
        return (object) $this->toArray();
    }
}