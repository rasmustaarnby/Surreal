<?php

namespace Tests\Feature\Query;

use Tests\AssertsMockConnection;
use Tests\TestCase;

class BuilderSelectRelatedTest extends TestCase
{
    use AssertsMockConnection;

    public function test_selects_relations(): void
    {
        $this->expectsMessage('SELECT *, ->knows->user.*, <-knows<-user.* FROM `user` LIMIT 1');

        $this->surreal->table('user')->select('*', '->knows->user.*', '<-knows<-user.*')->first();
    }

    public function test_selects_relation_with_related(): void
    {
        $this->expectsMessage('SELECT *, ->knows->user.* FROM `user`');

        $this->surreal->table('user')->related('->knows->user')->get();
    }

    public function test_selects_relation_with_related_array(): void
    {
        $this->expectsMessage('SELECT *, ->knows->user.* FROM `user`');

        $this->surreal->table('user')->related(['->knows', '->user'])->get();
    }

    public function test_selects_relation_with_closure(): void
    {
        $this->expectsMessage('SELECT *, ->(knows WHERE `family` = $?)->user.* FROM `user`', [true]);

        $this->surreal->table('user')->related([
            '->knows' => fn($query) => $query->where('family', true),
            '->user'
        ])->get();
    }

    public function test_selects_relation_with_closure_and_flags(): void
    {
        $this->expectsMessage('SELECT *, ->(knows WHERE `family` = $?)->user.* FROM `user` TIMEOUT 5s PARALLEL', [true]);

        $this->surreal->table('user')
            ->timeout(5)
            ->parallel()
            ->related([
                '->knows' => fn($query) => $query->where('family', true),
                '->user'
            ])->get();
    }

    public function test_selects_relation_with_closure_on_last_and_flags(): void
    {
        $this->expectsMessage('SELECT *, ->knows->(user WHERE `age` = $?).* FROM `user` TIMEOUT 5s PARALLEL', [28]);

        $this->surreal->table('user')
            ->timeout(5)
            ->parallel()
            ->related([
                '->knows',
                '->user' => fn($query) => $query->where('age', 28)
            ])->get();
    }

    public function test_selects_relation_with_dynamic_methods(): void
    {
        $this->expectsMessage('SELECT *, ->knows->(user WHERE `age` = $?).* FROM `user` TIMEOUT 5s PARALLEL', [28]);

        $this->surreal->table('user')
            ->timeout(5)
            ->parallel()
            ->related()
            ->toKnows
            ->toUser(fn($query) => $query->where('age', 28))
            ->get();
    }
}
