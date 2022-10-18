<?php

namespace Tests\Feature\Query;

use Tests\AssertsMockConnection;
use Tests\TestCase;

class BuilderDeleteTest extends TestCase
{
    use AssertsMockConnection;

    public function test_delete(): void
    {
        $this->expectsMessage('DELETE user');

        $this->surreal->delete('DELETE user');
    }

    public function test_delete_with_bindings(): void
    {
        $this->expectsMessage('DELETE user WHERE foo = $?', ['foo' => 'bar']);

        $this->surreal->delete('DELETE user WHERE foo = $?', ['foo' => 'bar']);
    }

    public function test_query_delete(): void
    {
        $this->expectsMessage('DELETE FROM `user`');

        $this->surreal->table('user')->delete();
    }

    public function test_query_delete_with_bindings(): void
    {
        $this->expectsMessage('DELETE FROM `user` WHERE `foo` = $?', ['bar']);

        $this->surreal->table('user')->where('foo', 'bar')->delete();
    }

    public function test_query_delete_id_directly(): void
    {
        $this->expectsMessage('DELETE "user:id"');

        $this->surreal->id('user:id')->delete();
    }

    public function test_query_delete_id_as_argument(): void
    {
        $this->expectsMessage('DELETE FROM `user` WHERE `id` = $?', ['user:id']);

        $this->surreal->table('user')->delete('user:id');
    }
}
