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

    public static function forbidden(string $message = 'Нет доступа'): self
    {
        return new self(403, $message);
    }

    public static function expired(): self
    {
        return new self(419, 'Страница устарела. Обновите ее и повторите действие');
    }

    public function status(): int
    {
        return $this->status;
    }
}
