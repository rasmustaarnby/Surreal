<?php

namespace Laragear\Surreal\Query;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Query\Expression;
use Stringable;

class Future implements Stringable
{
    /**
     * Create a new Future instance.
     *
     * @param  \Illuminate\Contracts\Database\Query\Builder|\Illuminate\Database\Query\Expression  $raw
     */
    public function __construct(protected Builder|Expression $raw)
    {
        //
    }

    /**
     * Crete a new Future to execute only when the data is retrieved
     *
     * @param  string  $statement
     * @return static
     */
    public static function be(string $statement): static
    {
        return new static(new Expression($statement));
    }

    /**
     * Return a string representation of the object.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->raw instanceof Builder
            ? $this->raw->toSql()
            : (string) $this->raw;
    }
}