<?php

namespace Tests\Feature\Query;

use Tests\AssertsMockConnection;
use Tests\TestCase;

class BuilderSplitTest extends TestCase
{
    use AssertsMockConnection;

    public function test_splits_records(): void
    {
        $this->expectsMessage('SELECT * FROM `user` SPLIT AT `foo`');

        $this->surreal->table('user')->split('foo')->get();
    }

    public function test_splits_multiple_columns(): void
    {
        $this->expectsMessage('SELECT * FROM `user` SPLIT AT `foo`, `bar`');
        $this->expectsMessage('SELECT * FROM `user` SPLIT AT `baz`, `quz`');

        $this->surreal->table('user')->split('foo', 'bar')->get();
        $this->surreal->table('user')->split(['baz', 'quz'])->get();
    }
}
