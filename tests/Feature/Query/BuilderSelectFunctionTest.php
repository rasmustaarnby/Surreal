<?php

namespace Tests\Feature\Query;

use Laragear\Surreal\Functions\SurrealFunction;
use Tests\AssertsMockConnection;
use Tests\TestCase;

class BuilderSelectFunctionTest extends TestCase
{
    use AssertsMockConnection;

    public function test_select_with_function(): void
    {
        $this->expectsMessage('SELECT foo::bar($?) FROM `user`', ['baz']);

        $this->surreal->table('user')->get([
            'foo' => SurrealFunction::make('foo::bar($?)', ['baz'])
        ]);
    }
}
