<?php

namespace Laragear\Surreal\Functions;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Str;
use JsonSerializable;
use Laragear\Surreal\Query\SurrealGrammar;
use Stringable;

class SurrealFunction extends Expression implements Stringable, JsonSerializable, Jsonable
{
    /**
     * Create a new Surreal Function instance.
     *
     * @param  string  $value
     * @param  array  $bindings
     * @param  string  $as
     */
    public function __construct(protected $value, readonly public array $bindings, protected string $as = '')
    {
        parent::__construct($value);
    }

    /**
     * Sets the function to be custom named on the query.
     *
     * @param  string  $name
     * @return $this
     */
    public function as(string $name): static
    {
        $this->as = $name;

        return $this;
    }

    /**
     * Returns the raw expression of the Surreal Function, without bindings.
     *
     * @return string
     */
    public function expression(): string
    {
        return $this->value . ($this->as ? " AS $this->as" : '');
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toSql();
    }

    /**
     * Returns the function as an SurrealSQL Expression.
     *
     * @return string
     */
    public function toSql(): string
    {
        return Str::replaceArray(SurrealGrammar::BINDING_STRING, $this->bindings, $this->expression());
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return string
     */
    public function jsonSerialize(): string
    {
        return $this->toSql();
    }

    /**
     * Create a new Surreal Function instance.
     *
     * @param  string  $expression
     * @param  array  $bindings
     * @return static
     */
    public static function make(string $expression, array $bindings = []): static
    {
        // This "replace" may seem redundant, but allows to replace the binding in the future.
        return new static(Str::replace('$?', SurrealGrammar::BINDING_STRING, $expression), $bindings);
    }
}
