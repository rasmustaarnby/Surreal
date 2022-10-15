<?php

namespace Tests\Feature\Query;

use RuntimeException;
use Tests\AssertsMockConnection;
use Tests\TestCase;

class BuilderInsertTest extends TestCase
{
    use AssertsMockConnection;

    public function test_inserts_without_values_doesnt_call_database(): void
    {
        $this->client->expects('send')->never();

        $this->surreal->table('user')->insert([]);
    }

    public function test_inserts_record_to_table_with_values(): void
    {
        $this->expectsMessage('INSERT INTO `user` (`foo`) VALUES ($?)', ['bar']);

        $this->surreal->table('user')->insert(['foo' => 'bar']);
    }

    public function test_insert_multiple_records(): void
    {
        $this->expectsMessage(
            'INSERT INTO `user` (`bar`, `foo`) VALUES ($?, $?), ($?, $?)', ['beta', 'alpha', 'delta', 'charlie']
        );

        $this->surreal->table('user')->insert([
            ['foo' => 'alpha', 'bar' => 'beta'],
            ['foo' => 'charlie', 'bar' => 'delta']
        ]);
    }

    public function test_insert_record_with_ignore(): void
    {
        $this->expectsMessage('INSERT IGNORE INTO `user` (`foo`) VALUES ($?)', ['bar']);

        $this->surreal->table('user')->insertOrIgnore(['foo' => 'bar']);
    }

    public function test_insert_record_updating_duplicate_key(): void
    {
        $this->expectsMessage(
            'INSERT INTO `user` (`foo`, `bar`) VALUES ($?, $?) ON DUPLICATE KEY UPDATE `baz` = $?',
            ['alpha', 'beta', 'charlie']
        );

        $this->surreal->table('user')->upsert(['foo' => 'alpha', 'bar' => 'beta'], 'id', ['baz' => 'charlie']);
    }

    public function test_insert_record_updating_throws_without_update_values(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('SurrealDB UPSERT requires the keys to update.');

        $this->surreal->table('user')->upsert(['foo' => 'alpha', 'bar' => 'beta'], 'id');
    }

    public function test_insert_record_updating_throws_when_not_id_duplicate(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('SurrealDB only supports upsert on the [id] primary key, `invalid` given.');

        $this->surreal->table('user')->upsert(['foo' => 'alpha', 'bar' => 'beta'], 'invalid');
    }
}
