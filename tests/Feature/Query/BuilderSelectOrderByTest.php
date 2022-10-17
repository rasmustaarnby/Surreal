<?php

namespace Tests\Feature\Query;

use Tests\AssertsMockConnection;
use Tests\TestCase;

class BuilderSelectOrderByTest extends TestCase
{
    use AssertsMockConnection;

    public function test_order_default(): void
    {
        $this->expectsMessage('SELECT * FROM `user` ORDER BY `foo` ASC');
        $this->expectsMessage('SELECT * FROM `user` ORDER BY `foo` DESC');

        $this->surreal->table('user')->orderBy('foo')->get();
        $this->surreal->table('user')->orderBy('foo', 'desc')->get();
    }

    public function test_multiple_orders(): void
    {
        $this->expectsMessage('SELECT * FROM `user` ORDER BY `foo` ASC, `bar` DESC');
        $this->expectsMessage('SELECT * FROM `user` ORDER BY `foo` DESC, `bar` ASC');

        $this->surreal->table('user')->orderBy('foo')->orderBy('bar', 'desc')->get();
        $this->surreal->table('user')->orderBy('foo', 'desc')->orderBy('bar')->get();
    }

    public function test_order_randomly(): void
    {
        $this->expectsMessage('SELECT * FROM `user` ORDER BY RAND()');

        $this->surreal->table('user')->inRandomOrder()->get();
    }

    public function test_order_by_collate(): void
    {
        $this->expectsMessage('SELECT * FROM `user` ORDER BY `foo` COLLATION ASC');
        $this->expectsMessage('SELECT * FROM `user` ORDER BY `foo` COLLATION DESC');

        $this->surreal->table('user')->orderByCollate('foo')->get();
        $this->surreal->table('user')->orderByCollate('foo', 'desc')->get();
    }

    public function test_multiple_order_by_collate(): void
    {

        $this->expectsMessage('SELECT * FROM `user` ORDER BY `foo` COLLATION ASC, `bar` COLLATION DESC');
        $this->expectsMessage('SELECT * FROM `user` ORDER BY `foo` COLLATION DESC, `bar` COLLATION ASC');

        $this->surreal->table('user')->orderByCollate('foo')->orderByCollate('bar', 'desc')->get();
        $this->surreal->table('user')->orderByCollate('foo', 'desc')->orderByCollate('bar')->get();
    }

    public function test_order_by_numeric(): void
    {
        $this->expectsMessage('SELECT * FROM `user` ORDER BY `foo` NUMERIC ASC');
        $this->expectsMessage('SELECT * FROM `user` ORDER BY `foo` NUMERIC DESC');

        $this->surreal->table('user')->orderByNumeric('foo')->get();
        $this->surreal->table('user')->orderByNumeric('foo', 'desc')->get();
    }

    public function test_multiple_order_by_numeric(): void
    {
        $this->expectsMessage('SELECT * FROM `user` ORDER BY `foo` NUMERIC ASC, `bar` NUMERIC DESC');
        $this->expectsMessage('SELECT * FROM `user` ORDER BY `foo` NUMERIC DESC, `bar` NUMERIC ASC');

        $this->surreal->table('user')->orderByNumeric('foo')->orderByNumeric('bar', 'desc')->get();
        $this->surreal->table('user')->orderByNumeric('foo', 'desc')->orderByNumeric('bar')->get();
    }

    public function test_multiple_varied_orders(): void
    {
        $this->expectsMessage('SELECT * FROM `user` ORDER BY `foo` NUMERIC ASC, `bar` DESC, `baz` COLLATION ASC');

        $this->surreal->table('user')
            ->orderByNumeric('foo')->orderBy('bar', 'desc')->orderByCollate('baz')
            ->get();
    }
}
