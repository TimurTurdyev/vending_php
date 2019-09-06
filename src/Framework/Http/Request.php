<?php
declare(strict_types=1);

namespace Framework\Http;

final class Request
{
    /** @var string */
    private $method;

    /** @var string */
    private $uri;

    /** @var string */
    private $scriptName;

    /** @var array */
    private $post;

    public function __construct(string $method, string $uri, string $scriptName = '/index.php', array $post = [])
    {
        $this->method = strtoupper($method);
        $this->uri = $uri;
        $this->scriptName = $scriptName;
        $this->post = $post;
    }

    public static function fromGlobals(): self
    {
        return new self(
            $_SERVER['REQUEST_METHOD'] ?? 'GET',
            $_SERVER['REQUEST_URI'] ?? '/',
            $_SERVER['SCRIPT_NAME'] ?? '/index.php',
            $_POST
        );
    }

    public function method(): string
    {
        return $this->method;
    }

    public function basePath(): string
    {
        return rtrim(str_replace('\\', '/', dirname($this->scriptName)), '/');
    }

    // путь внутри приложения: /shop/index.php/buy -> /buy
    public function path(): string
    {
        $path = (string)parse_url($this->uri, PHP_URL_PATH);
        $base = $this->basePath();

        if ($base !== '' && strpos($path, $base) === 0) {
            $path = substr($path, strlen($base));
        }

        if (strpos($path, '/index.php') === 0) {
            $path = substr($path, strlen('/index.php'));
        }

        return '/' . trim($path, '/');
    }

    public function post(string $key, $default = null)
    {
        return $this->post[$key] ?? $default;
    }
}
