<?php

namespace Tests\Feature\Query;

use Tests\AssertsMockConnection;
use Tests\TestCase;

class BuilderUpdateTest extends TestCase
{
    use AssertsMockConnection;

    public function test_update_operation(): void
    {
        $this->expectsMessage('UPDATE user:1 SET fred = $?', ['thud']);

        $this->surreal->update('UPDATE user:1 SET fred = $?', ['thud']);
    }

    public function test_updates_through_query(): void
    {
        $this->expectsMessage('UPDATE `user` SET `fred` = $? WHERE `foo` = $?', ['thud', 'bar']);

        $this->surreal->table('user')->where('foo', 'bar')->update(['fred' => 'thud']);
    }

    public function test_updates_through_query_using_id(): void
    {
        $this->expectsMessage('UPDATE "user:1" SET `fred` = $? WHERE `foo` = $?', ['thud', 'bar']);

        $this->surreal->id('user:1')->where('foo', 'bar')->update(['fred' => 'thud']);
    }
}
