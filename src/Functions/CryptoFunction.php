<?php

namespace Laragear\Surreal\Functions;

class CryptoFunction
{
    /**
     * Returns the md5 hash of a value.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function md5(string $value): SurrealFunction
    {
        return SurrealFunction::make('crypto::md5($?)', [$value]);
    }

    /**
     * Returns the sha1 hash of a value.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function sha1(string $value): SurrealFunction
    {
        return SurrealFunction::make('crypto::sha1($?)', [$value]);
    }

    /**
     * Returns the sha256 hash of a value.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function sha256(string $value): SurrealFunction
    {
        return SurrealFunction::make('crypto::sha256($?)', [$value]);
    }

    /**
     * Returns the sha512 hash of a value.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function sha512(string $value): SurrealFunction
    {
        return SurrealFunction::make('crypto::sha512($?)', [$value]);
    }

    /**
     * Compares an argon2 hash to a password.
     *
     * @param  string  $value
     * @param  string  $compared
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function argon2Compare(string $value, string $compared): SurrealFunction
    {
        return SurrealFunction::make('crypto::argon2::compare($?, $?)', [$value, $compared]);
    }

    /**
     * Generates a new argon2 hashed password.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function argon2Generate(string $value): SurrealFunction
    {
        return SurrealFunction::make('crypto::argon2::generate($?)', [$value]);
    }

    /**
     * Compares an pbkdf2 hash to a password.
     *
     * @param  string  $value
     * @param  string  $compared
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function pbkdf2Compare(string $value, string $compared): SurrealFunction
    {
        return SurrealFunction::make('crypto::pbkdf2::compare($?, $?)', [$value, $compared]);
    }

    /**
     * Generates a new pbkdf2 hashed password
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function pbkdf2Generate(string $value): SurrealFunction
    {
        return SurrealFunction::make('crypto::pbkdf2::generate($?)', [$value]);
    }

    /**
     * Compares an scrypt hash to a password.
     *
     * @param  string  $value
     * @param  string  $compared
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function scryptCompare(string $value, string $compared): SurrealFunction
    {
        return SurrealFunction::make('crypto::scrypt::compare($?, $?)', [$value, $compared]);
    }

    /**
     * Generates a new scrypt hashed password.
     *
     * @param  string  $value
     * @return \Laragear\Surreal\Functions\SurrealFunction
     */
    public function scryptGenerate(string $value): SurrealFunction
    {
        return SurrealFunction::make('crypto::scrypt::generate($?)', [$value]);
    }
}
