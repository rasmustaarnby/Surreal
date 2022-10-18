<?php

namespace Illuminate\Database\Query {

    /**
     * @method $this return(\Laragear\Surreal\Query\ReturnType|string|array ...$type)
     * @method $this returnNone()
     * @method $this timeout(\DateInterval|\Carbon\CarbonInterval|int $timeout)
     * @method $this parallel()
     * @method $this split(...$keys)
     * @method $this fetch(...$keys)
     * @method $this orderByCollate($field, $direction = 'asc')
     * @method $this orderByNumeric($field, $direction = 'asc')
     * @method \Laragear\Surreal\Query\Related|$this related(array|string ...$related)
     * @method \Illuminate\Support\Collection|null create(array $attributes = [])
     * @method \Laragear\Surreal\Query\RelateTo relateTo($relatedId)
     */
    class Builder
    {
        //
    }
}
