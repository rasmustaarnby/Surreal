<?php

namespace Tests\Feature\Query;

use Tests\AssertsMockConnection;
use Tests\TestCase;

class BuilderFetchTest extends TestCase
{
    use AssertsMockConnection;

    public function test_fetch_relation(): void
    {
        $this->expectsMessage('SELECT * FROM "user:1" FETCH `foo`');

        $this->surreal->id('user:1')->fetch('foo')->get();
    }

    public function test_fetch_multiple_relations(): void
    {
        $this->expectsMessage('SELECT * FROM "user:1" FETCH `foo`, `bar`');
        $this->expectsMessage('SELECT * FROM "user:1" FETCH `baz`, `quz`');

        $this->surreal->id('user:1')->fetch('foo', 'bar')->get();
        $this->surreal->id('user:1')->fetch('baz', 'quz')->get();
    }
}
