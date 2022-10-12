<?php

namespace Laragear\Surreal\JsonRpc;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use Laragear\Surreal\Query\SurrealGrammar;
use Stringable;
use function array_keys;
use function json_encode;

class QueryMessage implements Stringable, JsonSerializable, Jsonable, Arrayable
{
    /**
     * Create a new Statement.
     *
     * @param  string  $id
     * @param  string  $method
     * @param  array{\Laragear\Surreal\JsonRpc\Statement,\Laragear\Surreal\JsonRpc\QueryParameters}  $params
     */
    public function __construct(
        readonly public string $id,
        readonly public string $method,
        #[ArrayShape(["Laragear\\Surreal\\JsonRpc\\Statement", "Laragear\\Surreal\\JsonRpc\\QueryParameters"])]
        readonly public array $params,
    ) {
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
     * Get the instance as an array.
     *
     * @return array{id:string,method:string,parameters:array}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'method' => $this->method,
            'params' => $this->params,
        ];
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
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Compile a new statement.
     *
     * @param  string  $statement
     * @param  array  $bindings
     * @return static
     */
    public static function queryWithUlid(string $statement, array $bindings): static
    {
        return new static(Str::ulid(), 'query', [
            new Statement($statement, array_keys($bindings)),
            new QueryParameters($bindings)
        ]);
    }
}