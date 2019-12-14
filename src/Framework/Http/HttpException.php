<?php
declare(strict_types=1);

namespace Framework\Http;

use RuntimeException;

final class HttpException extends RuntimeException
{
    /** @var int */
    private $status;

    public function __construct(int $status, string $message)
    {
        parent::__construct($message);
        $this->status = $status;
    }

    public static function notFound(string $message = 'Страница не найдена'): self
    {
        return new self(404, $message);
    }

    public function status(): int
    {
        return $this->status;
    }
}
