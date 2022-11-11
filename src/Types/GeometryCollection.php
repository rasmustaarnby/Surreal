<?php

namespace Laragear\Surreal\Types;

use Illuminate\Support\Collection;

class GeometryCollection extends Collection
{
    /**
     * Get the collection of items as a plain array.
     *
     * @return array{type:string,geometries:array{type:string,coordinates:array}}
     */
    public function toArray(): array
    {
        return [
            'type' => 'GeometryCollection',
            'geometries' => $this->map(static function (Geometry $geometry): array {
                return $geometry->toArray();
            })->all()
        ];
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array{type:string,geometries:array{type:string,coordinates:array}}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
