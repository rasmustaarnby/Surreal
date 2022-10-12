<?php

namespace Laragear\Surreal\Query;

use Laragear\Surreal\Functions\ArrayFunction;
use Laragear\Surreal\Functions\CountFunction;
use Laragear\Surreal\Functions\CryptoFunction;
use Laragear\Surreal\Functions\GeoFunction;
use Laragear\Surreal\Functions\HttpFunction;
use Laragear\Surreal\Functions\MathFunction;
use Laragear\Surreal\Functions\ParseFunction;
use Laragear\Surreal\Functions\RandomFunction;
use Laragear\Surreal\Functions\ScriptFunction;
use Laragear\Surreal\Functions\SessionFunction;
use Laragear\Surreal\Functions\StringFunction;
use Laragear\Surreal\Functions\TimeFunction;
use Laragear\Surreal\Functions\TypeFunction;
use Laragear\Surreal\Functions\ValidationFunction;

class Func
{
    public static function array(): ArrayFunction
    {
        return new ArrayFunction();
    }

    public static function count(): CountFunction
    {
        
    }

    public static function crypto(): CryptoFunction
    {

    }

    public static function geo(): GeoFunction
    {

    }

    public static function http(): HttpFunction
    {

    }

    public static function is(): ValidationFunction
    {

    }

    public static function math(): MathFunction
    {

    }

    public static function parse(): ParseFunction
    {

    }

    public static function rand(): RandomFunction
    {

    }

    public static function random(): RandomFunction
    {
        return static::rand();
    }

    public static function session(): SessionFunction
    {

    }

    public static function string(): StringFunction
    {

    }

    public static function str(): StringFunction
    {
        return static::string();
    }

    public static function time(): TimeFunction
    {

    }

    public static function type(): TypeFunction
    {

    }

    public static function js(): ScriptFunction
    {

    }

    public static function script(): ScriptFunction
    {
        return static::js();
    }
}