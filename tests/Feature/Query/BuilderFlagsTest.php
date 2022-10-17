<?php

namespace Tests\Feature\Query;

use Carbon\CarbonInterval;
use Laragear\Surreal\Query\ReturnType;
use Tests\AssertsMockConnection;
use Tests\TestCase;

class BuilderFlagsTest extends TestCase
{
    use AssertsMockConnection;

    public function test_return_is_default_by_default(): void
    {
        static::assertSame(ReturnType::Default, $this->surreal->query()->getGrammar()->return);
    }

    public function test_return_accepts_enum(): void
    {
        $query = $this->surreal->id('user:bar')->return(ReturnType::Before);

        static::assertSame(ReturnType::Before, $query->getGrammar()->return);
    }

    public function test_return_accepts_array_of_attributes(): void
    {
        $query = $this->surreal->id('user:bar')->return(['foo', 'bar']);

        static::assertSame(['foo', 'bar'], $query->getGrammar()->return);

        $query = $this->surreal->id('user:bar')->return('foo', 'bar');

        static::assertSame(['foo', 'bar'], $query->getGrammar()->return);
    }

    public function test_return_none_alias(): void
    {
        $query = $this->surreal->id('user:bar')->returnNone();

        static::assertSame(ReturnType::None, $query->getGrammar()->return);
    }

    public function test_return_create(): void
    {
        $this->expectsMessage('CREATE "user:bar" RETURN DIFF');
        $this->expectsMessage('CREATE "user:bar" RETURN NONE');
        $this->expectsMessage('CREATE "user:bar" RETURN BEFORE');
        $this->expectsMessage('CREATE "user:bar" RETURN AFTER');
        $this->expectsMessage('CREATE "user:bar" RETURN `foo`, `bar`');

        $this->surreal->id('user:bar')->return('diff')->create();
        $this->surreal->id('user:bar')->return('none')->create();
        $this->surreal->id('user:bar')->return('before')->create();
        $this->surreal->id('user:bar')->return('after')->create();
        $this->surreal->id('user:bar')->return('foo', 'bar')->create();
    }

    public function test_return_create_with_values(): void
    {
        $this->expectsMessage('CREATE "user:bar" CONTENT { "foo" : $? } RETURN DIFF', ['foo' => 'bar']);
        $this->expectsMessage('CREATE "user:bar" CONTENT { "foo" : $? } RETURN NONE', ['foo' => 'bar']);
        $this->expectsMessage('CREATE "user:bar" CONTENT { "foo" : $? } RETURN BEFORE', ['foo' => 'bar']);
        $this->expectsMessage('CREATE "user:bar" CONTENT { "foo" : $? } RETURN AFTER', ['foo' => 'bar']);
        $this->expectsMessage('CREATE "user:bar" CONTENT { "foo" : $? } RETURN `foo`, `bar`', ['foo' => 'bar']);

        $this->surreal->id('user:bar')->return('diff')->create(['foo' => 'bar']);
        $this->surreal->id('user:bar')->return('none')->create(['foo' => 'bar']);
        $this->surreal->id('user:bar')->return('before')->create(['foo' => 'bar']);
        $this->surreal->id('user:bar')->return('after')->create(['foo' => 'bar']);
        $this->surreal->id('user:bar')->return('foo', 'bar')->create(['foo' => 'bar']);
    }

    public function test_return_update(): void
    {
        $this->expectsMessage('UPDATE "user:bar" SET `foo` = $? RETURN DIFF', ['bar']);
        $this->expectsMessage('UPDATE "user:bar" SET `foo` = $? RETURN NONE', ['bar']);
        $this->expectsMessage('UPDATE "user:bar" SET `foo` = $? RETURN BEFORE', ['bar']);
        $this->expectsMessage('UPDATE "user:bar" SET `foo` = $? RETURN AFTER', ['bar']);
        $this->expectsMessage('UPDATE "user:bar" SET `foo` = $? RETURN `foo`, `bar`', ['bar']);

        $this->surreal->id('user:bar')->return('diff')->update(['foo' => 'bar']);
        $this->surreal->id('user:bar')->return('none')->update(['foo' => 'bar']);
        $this->surreal->id('user:bar')->return('before')->update(['foo' => 'bar']);
        $this->surreal->id('user:bar')->return('after')->update(['foo' => 'bar']);
        $this->surreal->id('user:bar')->return('foo', 'bar')->update(['foo' => 'bar']);
    }

    public function test_return_update_with_where(): void
    {
        $this->expectsMessage('UPDATE "user:bar" SET `foo` = $? WHERE `foo` = $? RETURN DIFF', ['bar', 'bar']);
        $this->expectsMessage('UPDATE "user:bar" SET `foo` = $? WHERE `foo` = $? RETURN NONE', ['bar', 'bar']);
        $this->expectsMessage('UPDATE "user:bar" SET `foo` = $? WHERE `foo` = $? RETURN BEFORE', ['bar', 'bar']);
        $this->expectsMessage('UPDATE "user:bar" SET `foo` = $? WHERE `foo` = $? RETURN AFTER', ['bar', 'bar']);
        $this->expectsMessage('UPDATE "user:bar" SET `foo` = $? WHERE `foo` = $? RETURN `foo`, `bar`', ['bar', 'bar']);

        $this->surreal->id('user:bar')->where('foo', 'bar')->return('diff')->update(['foo' => 'bar']);
        $this->surreal->id('user:bar')->where('foo', 'bar')->return('none')->update(['foo' => 'bar']);
        $this->surreal->id('user:bar')->where('foo', 'bar')->return('before')->update(['foo' => 'bar']);
        $this->surreal->id('user:bar')->where('foo', 'bar')->return('after')->update(['foo' => 'bar']);
        $this->surreal->id('user:bar')->where('foo', 'bar')->return('foo', 'bar')->update(['foo' => 'bar']);
    }

    public function test_return_delete(): void
    {
        $this->expectsMessage('DELETE "user:bar" RETURN DIFF');
        $this->expectsMessage('DELETE "user:bar" RETURN NONE');
        $this->expectsMessage('DELETE "user:bar" RETURN BEFORE');
        $this->expectsMessage('DELETE "user:bar" RETURN AFTER');
        $this->expectsMessage('DELETE "user:bar" RETURN `foo`, `bar`');

        $this->surreal->id('user:bar')->return('diff')->delete();
        $this->surreal->id('user:bar')->return('none')->delete();
        $this->surreal->id('user:bar')->return('before')->delete();
        $this->surreal->id('user:bar')->return('after')->delete();
        $this->surreal->id('user:bar')->return('foo', 'bar')->delete();
    }

    public function test_return_delete_with_where(): void
    {
        $this->expectsMessage('DELETE "user:bar" WHERE `foo` = $? RETURN DIFF', ['bar']);
        $this->expectsMessage('DELETE "user:bar" WHERE `foo` = $? RETURN NONE', ['bar']);
        $this->expectsMessage('DELETE "user:bar" WHERE `foo` = $? RETURN BEFORE', ['bar']);
        $this->expectsMessage('DELETE "user:bar" WHERE `foo` = $? RETURN AFTER', ['bar']);
        $this->expectsMessage('DELETE "user:bar" WHERE `foo` = $? RETURN `foo`, `bar`', ['bar']);

        $this->surreal->id('user:bar')->where('foo', 'bar')->return('diff')->delete();
        $this->surreal->id('user:bar')->where('foo', 'bar')->return('none')->delete();
        $this->surreal->id('user:bar')->where('foo', 'bar')->return('before')->delete();
        $this->surreal->id('user:bar')->where('foo', 'bar')->return('after')->delete();
        $this->surreal->id('user:bar')->where('foo', 'bar')->return('foo', 'bar')->delete();
    }

    public function test_return_delete_with_id(): void
    {
        $this->expectsMessage('DELETE FROM `user` WHERE `id` = $? RETURN DIFF', ['user:bar']);
        $this->expectsMessage('DELETE FROM `user` WHERE `id` = $? RETURN NONE', ['user:bar']);
        $this->expectsMessage('DELETE FROM `user` WHERE `id` = $? RETURN BEFORE', ['user:bar']);
        $this->expectsMessage('DELETE FROM `user` WHERE `id` = $? RETURN AFTER', ['user:bar']);
        $this->expectsMessage('DELETE FROM `user` WHERE `id` = $? RETURN `foo`, `bar`', ['user:bar']);

        $this->surreal->table('user')->return('diff')->delete('user:bar');
        $this->surreal->table('user')->return('none')->delete('user:bar');
        $this->surreal->table('user')->return('before')->delete('user:bar');
        $this->surreal->table('user')->return('after')->delete('user:bar');
        $this->surreal->table('user')->return('foo', 'bar')->delete('user:bar');
    }

    public function test_timeout_select(): void
    {
        $this->expectsMessage('SELECT * FROM "user:bar" TIMEOUT 5s');
        $this->expectsMessage('SELECT * FROM "user:bar" TIMEOUT 1w');
        $this->expectsMessage('SELECT * FROM "user:bar" TIMEOUT 300000µs');
        $this->expectsMessage('SELECT * FROM "user:bar" TIMEOUT 500µs');

        $this->surreal->id('user:bar')->timeout(5)->get();
        $this->surreal->id('user:bar')->timeout(CarbonInterval::create(0, weeks: 1))->get();
        $this->surreal->id('user:bar')->timeout(CarbonInterval::create(0)->millisecond(300))->get();
        $this->surreal->id('user:bar')->timeout(CarbonInterval::create(0, microseconds: 500))->get();
    }

    public function test_timeout_create(): void
    {
        $this->expectsMessage('CREATE "user:bar" TIMEOUT 5s');
        $this->expectsMessage('CREATE "user:bar" TIMEOUT 1w');
        $this->expectsMessage('CREATE "user:bar" TIMEOUT 300000µs');
        $this->expectsMessage('CREATE "user:bar" TIMEOUT 500µs');

        $this->surreal->id('user:bar')->timeout(5)->create();
        $this->surreal->id('user:bar')->timeout(CarbonInterval::create(0, weeks: 1))->create();
        $this->surreal->id('user:bar')->timeout(CarbonInterval::create(0)->millisecond(300))->create();
        $this->surreal->id('user:bar')->timeout(CarbonInterval::create(0, microseconds: 500))->create();
    }

    public function test_timeout_create_with_values(): void
    {
        $this->expectsMessage('CREATE "user:bar" CONTENT { "foo" : $? } TIMEOUT 5s', ['foo' => 'bar']);
        $this->expectsMessage('CREATE "user:bar" CONTENT { "foo" : $? } TIMEOUT 1w', ['foo' => 'bar']);
        $this->expectsMessage('CREATE "user:bar" CONTENT { "foo" : $? } TIMEOUT 300000µs', ['foo' => 'bar']);
        $this->expectsMessage('CREATE "user:bar" CONTENT { "foo" : $? } TIMEOUT 500µs', ['foo' => 'bar']);

        $this->surreal->id('user:bar')->timeout(5)->create(['foo' => 'bar']);
        $this->surreal->id('user:bar')->timeout(CarbonInterval::create(0, weeks: 1))->create(['foo' => 'bar']);
        $this->surreal->id('user:bar')->timeout(CarbonInterval::create(0)->millisecond(300))->create(['foo' => 'bar']);
        $this->surreal->id('user:bar')->timeout(CarbonInterval::create(0, microseconds: 500))->create(['foo' => 'bar']);
    }

    public function test_timeout_update(): void
    {
        $this->expectsMessage('UPDATE "user:bar" SET `foo` = $? TIMEOUT 5s', ['bar']);
        $this->expectsMessage('UPDATE "user:bar" SET `foo` = $? TIMEOUT 1w', ['bar']);
        $this->expectsMessage('UPDATE "user:bar" SET `foo` = $? TIMEOUT 300000µs', ['bar']);
        $this->expectsMessage('UPDATE "user:bar" SET `foo` = $? TIMEOUT 500µs', ['bar']);

        $this->surreal->id('user:bar')->timeout(5)->update(['foo' => 'bar']);
        $this->surreal->id('user:bar')->timeout(CarbonInterval::create(0, weeks: 1))->update(['foo' => 'bar']);
        $this->surreal->id('user:bar')->timeout(CarbonInterval::create(0)->millisecond(300))->update(['foo' => 'bar']);
        $this->surreal->id('user:bar')->timeout(CarbonInterval::create(0, microseconds: 500))->update(['foo' => 'bar']);
    }

    public function test_timeout_update_with_where(): void
    {
        $this->expectsMessage('UPDATE "user:bar" SET `foo` = $? WHERE `foo` = $? TIMEOUT 5s', ['bar', 'bar']);
        $this->expectsMessage('UPDATE "user:bar" SET `foo` = $? WHERE `foo` = $? TIMEOUT 1w', ['bar', 'bar']);
        $this->expectsMessage('UPDATE "user:bar" SET `foo` = $? WHERE `foo` = $? TIMEOUT 300000µs', ['bar', 'bar']);
        $this->expectsMessage('UPDATE "user:bar" SET `foo` = $? WHERE `foo` = $? TIMEOUT 500µs', ['bar', 'bar']);

        $this->surreal->id('user:bar')->where('foo', 'bar')->timeout(5)->update(['foo' => 'bar']);
        $this->surreal->id('user:bar')->where('foo', 'bar')->timeout(CarbonInterval::create(0, weeks: 1))->update(['foo' => 'bar']);
        $this->surreal->id('user:bar')->where('foo', 'bar')->timeout(CarbonInterval::create(0)->millisecond(300))->update(['foo' => 'bar']);
        $this->surreal->id('user:bar')->where('foo', 'bar')->timeout(CarbonInterval::create(0, microseconds: 500))->update(['foo' => 'bar']);
    }

    public function test_timeout_delete(): void
    {
        $this->expectsMessage('DELETE "user:bar" TIMEOUT 5s');
        $this->expectsMessage('DELETE "user:bar" TIMEOUT 1w');
        $this->expectsMessage('DELETE "user:bar" TIMEOUT 300000µs');
        $this->expectsMessage('DELETE "user:bar" TIMEOUT 500µs');

        $this->surreal->id('user:bar')->timeout(5)->delete();
        $this->surreal->id('user:bar')->timeout(CarbonInterval::create(0, weeks: 1))->delete();
        $this->surreal->id('user:bar')->timeout(CarbonInterval::create(0)->millisecond(300))->delete();
        $this->surreal->id('user:bar')->timeout(CarbonInterval::create(0, microseconds: 500))->delete();
    }

    public function test_timeout_delete_with_where(): void
    {
        $this->expectsMessage('DELETE "user:bar" WHERE `foo` = $? TIMEOUT 5s', ['bar']);
        $this->expectsMessage('DELETE "user:bar" WHERE `foo` = $? TIMEOUT 1w', ['bar']);
        $this->expectsMessage('DELETE "user:bar" WHERE `foo` = $? TIMEOUT 300000µs', ['bar']);
        $this->expectsMessage('DELETE "user:bar" WHERE `foo` = $? TIMEOUT 500µs', ['bar']);

        $this->surreal->id('user:bar')->where('foo', 'bar')->timeout(5)->delete();
        $this->surreal->id('user:bar')->where('foo', 'bar')->timeout(CarbonInterval::create(0, weeks: 1))->delete();
        $this->surreal->id('user:bar')->where('foo', 'bar')->timeout(CarbonInterval::create(0)->millisecond(300))->delete();
        $this->surreal->id('user:bar')->where('foo', 'bar')->timeout(CarbonInterval::create(0, microseconds: 500))->delete();
    }

    public function test_timeout_delete_with_id(): void
    {
        $this->expectsMessage('DELETE FROM `user` WHERE `id` = $? TIMEOUT 5s', ['user:bar']);
        $this->expectsMessage('DELETE FROM `user` WHERE `id` = $? TIMEOUT 1w', ['user:bar']);
        $this->expectsMessage('DELETE FROM `user` WHERE `id` = $? TIMEOUT 300000µs', ['user:bar']);
        $this->expectsMessage('DELETE FROM `user` WHERE `id` = $? TIMEOUT 500µs', ['user:bar']);

        $this->surreal->table('user')->timeout(5)->delete('user:bar');
        $this->surreal->table('user')->timeout(CarbonInterval::create(0, weeks: 1))->delete('user:bar');
        $this->surreal->table('user')->timeout(CarbonInterval::create(0)->millisecond(300))->delete('user:bar');
        $this->surreal->table('user')->timeout(CarbonInterval::create(0, microseconds: 500))->delete('user:bar');
    }

    public function test_parallel_select(): void
    {
        $this->expectsMessage('SELECT * FROM "user:bar" PARALLEL');

        $this->surreal->id('user:bar')->parallel()->get();
    }

    public function test_parallel_create(): void
    {
        $this->expectsMessage('CREATE "user:bar" PARALLEL');

        $this->surreal->id('user:bar')->parallel()->create();
    }

    public function test_parallel_create_with_values(): void
    {
        $this->expectsMessage('CREATE "user:bar" CONTENT { "foo" : $? } PARALLEL', ['foo' => 'bar']);

        $this->surreal->id('user:bar')->parallel()->create(['foo' => 'bar']);
    }

    public function test_parallel_update(): void
    {
        $this->expectsMessage('UPDATE "user:bar" SET `foo` = $? PARALLEL', ['bar']);

        $this->surreal->id('user:bar')->parallel()->update(['foo' => 'bar']);
    }

    public function test_parallel_update_with_where(): void
    {
        $this->expectsMessage('UPDATE "user:bar" SET `foo` = $? WHERE `foo` = $? PARALLEL', ['bar', 'bar']);

        $this->surreal->id('user:bar')->where('foo', 'bar')->parallel()->update(['foo' => 'bar']);
    }

    public function test_parallel_delete(): void
    {
        $this->expectsMessage('DELETE "user:bar" PARALLEL');

        $this->surreal->id('user:bar')->parallel()->delete();
    }

    public function test_parallel_delete_with_where(): void
    {
        $this->expectsMessage('DELETE "user:bar" WHERE `foo` = $? PARALLEL', ['bar']);

        $this->surreal->id('user:bar')->where('foo', 'bar')->parallel()->delete();
    }

    public function test_parallel_delete_with_id(): void
    {
        $this->expectsMessage('DELETE FROM `user` WHERE `id` = $? PARALLEL', ['user:bar']);

        $this->surreal->table('user')->parallel()->delete('user:bar');
    }

    public function test_all_flags_with_select(): void
    {
        $this->expectsMessage('SELECT * FROM "user:bar" TIMEOUT 5s PARALLEL');

        $this->surreal->id('user:bar')->timeout(5)->return('diff')->parallel()->get();
    }

    public function test_all_flags_with_create(): void
    {
        $this->expectsMessage('CREATE "user:bar" RETURN DIFF TIMEOUT 5s PARALLEL');

        $this->surreal->id('user:bar')->timeout(5)->return('diff')->parallel()->create();
    }

    public function test_all_flags_with_update(): void
    {
        $this->expectsMessage('UPDATE "user:bar" SET `foo` = $? RETURN DIFF TIMEOUT 5s PARALLEL', ['bar']);

        $this->surreal->id('user:bar')->timeout(5)->return('diff')->parallel()->update(['foo' => 'bar']);
    }

    public function test_all_flags_with_delete(): void
    {
        $this->expectsMessage('DELETE "user:bar" RETURN DIFF TIMEOUT 5s PARALLEL');

        $this->surreal->id('user:bar')->timeout(5)->return('diff')->parallel()->delete();
    }
}
