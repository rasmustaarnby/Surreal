<?php

namespace Laragear\Surreal\JsonRpc;

use Amp\Websocket\WebsocketMessage;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use function json_decode;
use const JSON_THROW_ON_ERROR;

class ServerMessage
{
    /**
     * Create a new JSON RPC Message
     *
     * @param  string  $id
     * @param  \Illuminate\Support\Collection  $result
     * @param  int|null  $errorCode
     * @param  string|null  $errorMessage
     */
    final public function __construct(
        readonly public string $id,
        readonly public Collection $result,
        readonly public ?int $errorCode,
        readonly public ?string $errorMessage,
    ) {
        //
    }

    /**
     * Check if the server message is an error.
     *
     * @return bool
     */
    public function isError(): bool
    {
        return $this->errorCode !== null;
    }

    /**
     * Create a new Server Message instance from
     *
     * @param  \Amp\Websocket\WebsocketMessage  $message
     * @return static
     * @throws \Amp\ByteStream\StreamException
     * @throws \Amp\Websocket\ClosedException
     * @throws \JsonException
     */
    public static function fromJson(WebsocketMessage $message): static
    {
        $content = json_decode((string) $message->read(), true, 512, JSON_THROW_ON_ERROR);

        return new static(
            Arr::get($content, 'id'),
            new Collection(Arr::get($content, 'result.0', [])),
            Arr::get($content, 'error.code'),
            Arr::get($content, 'error.message')
        );
    }
}