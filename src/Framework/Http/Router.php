<?php
declare(strict_types=1);

namespace Framework\Http;

final class Router
{
    /** @var array метод => путь => [класс контроллера, метод] */
    private $routes = [];

    public function get(string $path, array $handler): self
    {
        return $this->add('GET', $path, $handler);
    }

    public function post(string $path, array $handler): self
    {
        return $this->add('POST', $path, $handler);
    }

    public function add(string $method, string $path, array $handler): self
    {
        $this->routes[strtoupper($method)][$path] = $handler;

        return $this;
    }

    public function match(string $method, string $path): ?array
    {
        return $this->routes[$method][$path] ?? null;
    }
}
