<?php
declare(strict_types=1);

namespace Framework\Http;

final class Response
{
    /** @var string */
    private $body;

    /** @var int */
    private $status;

    /** @var string[] */
    private $headers;

    public function __construct(string $body = '', int $status = 200, array $headers = [])
    {
        $this->body = $body;
        $this->status = $status;
        $this->headers = $headers + ['Content-Type' => 'text/html; charset=utf-8'];
    }

    public static function redirect(string $url): self
    {
        return new self('', 303, ['Location' => $url]);
    }

    public function status(): int
    {
        return $this->status;
    }

    public function header(string $name): ?string
    {
        return $this->headers[$name] ?? null;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function send(): void
    {
        http_response_code($this->status);

        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        }

        echo $this->body;
    }
}
