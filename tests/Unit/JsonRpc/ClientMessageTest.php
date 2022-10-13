<?php

namespace Tests\Unit\JsonRpc;

use Illuminate\Support\Str;
use Laragear\Surreal\JsonRpc\ClientMessage;
use Laragear\Surreal\JsonRpc\QueryParameters;
use Laragear\Surreal\JsonRpc\Statement;
use PHPUnit\Framework\TestCase;
use function json_encode;

class ClientMessageTest extends TestCase
{
    public function test_creates_query_with_ulid_id(): void
    {
        $message = ClientMessage::queryWithUlid('foo', ['bar' => 'quz']);

        static::assertTrue(Str::isUlid($message->id));

        static::assertInstanceOf(Statement::class, $message->params[0]);
        static::assertSame('foo', $message->params[0]->statement);
        static::assertSame(['bar'], $message->params[0]->bindingKeys);

        static::assertInstanceOf(QueryParameters::class, $message->params[1]);
        static::assertSame(['bar' => 'quz'], $message->params[1]->parameters);
    }

    public function test_serializes_to_json(): void
    {
        $message = new ClientMessage('foo', 'bar', ['quz']);

        static::assertSame('{"id":"foo","method":"bar","params":["quz"]}', (string) $message);
        static::assertSame('{"id":"foo","method":"bar","params":["quz"]}', $message->toJson());
        static::assertSame('{"id":"foo","method":"bar","params":["quz"]}', json_encode($message));
    }
}
