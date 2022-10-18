<?php

namespace Laragear\Surreal\Query\Concerns;

use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laragear\Surreal\Surreal;
use RuntimeException;
use function array_key_last;
use function implode;
use function is_int;
use function substr;

trait SelectRelatedRelations
{
    /**
     * Compile the related records
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string
     */
    public function compileGraphEdges(Builder $query)
    {
        if (!empty($query->joins['edges'])) {
            $edges = [];

            foreach ((array) $query->joins['edges'] as $chain) {
                $edges[] = $this->compileGraphEdgeChain($query, $chain);
            }

            return implode(', ', $edges);
        }
    }

    /**
     * Compiles a chain (traversal) to a related record.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $chain
     * @return string
     */
    protected function compileGraphEdgeChain(Builder $query, array $chain)
    {
        $edges = [];

        foreach ($chain as $key => $value) {
            if (is_int($key)) {
                [$key, $value] = [$value, null];
            }

            if (Surreal::isNotEdge($key)) {
                throw new RuntimeException("The [$key] is not a valid graph edge ([<-/->]table).");
            }

            if ($value instanceof Closure) {
                $edges[] = $this->compileRelationQuery($query, $key, $value);

                continue;
            }

            $edges[] = $key;
        }

        // If the last edge is not picking any attribute from the relation, set all.
        if (! Str::contains(Arr::last($edges), '.')) {
            $edges[array_key_last($edges)] = Str::finish($edges[array_key_last($edges)], '.*');
        }

        return implode($edges);
    }

    /**
     * Compile the relation query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  string  $segment
     * @param  \Closure(\Laragear\Surreal\Query\Builder):void $callback
     * @return string
     */
    protected function compileRelationQuery(Builder $query, $segment, $callback)
    {
        $last = substr($segment, 2);

        $newQuery = $query->newQuery();

        $callback($newQuery->from('surreal_relation_placeholder'));

        // Add a placeholder marker to replace it later.
        $subQuery = Str::after(
            $newQuery->getGrammar()->compileSelect($newQuery), '`surreal_relation_placeholder` '
        );

        $query->mergeBindings($newQuery);

        return substr($segment, 0, 2)."($last $subQuery)";
    }
}
