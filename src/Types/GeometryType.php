<?php

namespace Laragear\Surreal\Types;

enum GeometryType: string
{
    case Point = 'Point';
    case Line = 'LineString';
    case Polygon = 'Polygon';
    case MultiPoint = 'MultiPoint';
    case MultiLine = 'MultiLinestring';
    case MultiPolygon = 'MultiPolygon';
}