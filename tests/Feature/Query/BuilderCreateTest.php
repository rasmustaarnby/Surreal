<?php

namespace Tests\Feature\Query;

use Tests\AssertsMockConnection;
use Tests\TestCase;

class BuilderCreateTest extends TestCase
{
    use AssertsMockConnection;

    public function test_creates_default_record(): void
    {
        $this->expectsMessage('CREATE `user`');

        $this->surreal->table('user')->create();
    }

    public function test_creates_record_with_values(): void
    {
        $this->expectsMessage('CREATE `user` CONTENT { "foo" : $? }', ['foo' => 'bar']);

        $this->surreal->table('user')->create(['foo' => 'bar']);
    }

    public function test_creates_record_id_without_values(): void
    {
        $this->expectsMessage('CREATE "user:bar"');

        $this->surreal->id('user:bar')->create();
    }

    public function test_creates_record_id_with_values(): void
    {
        $this->expectsMessage('CREATE "user:bar" CONTENT { "foo" : $? }', ['foo' => 'bar']);

        $this->surreal->id('user:bar')->create(['foo' => 'bar']);
    }
}
