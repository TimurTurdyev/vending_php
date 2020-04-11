<?php
declare(strict_types=1);

namespace Framework\Http;

use Framework\Storage\Storage;

final class Csrf
{
    private const KEY = '_csrf';

    /** @var Storage */
    private $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function token(): string
    {
        $token = $this->storage->get(self::KEY);

        if (!is_string($token)) {
            $token = bin2hex(random_bytes(16));
            $this->storage->set(self::KEY, $token);
        }

        return $token;
    }

    public function check(Request $request): void
    {
        if ($request->method() === 'POST' && !hash_equals($this->token(), (string)$request->post('_token'))) {
            throw HttpException::expired();
        }
    }
}
