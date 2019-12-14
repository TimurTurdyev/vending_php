<?php
declare(strict_types=1);

namespace Framework\Http;

final class Router
{
    /** @var array[] [метод, регулярное выражение, обработчик] */
    private $routes = [];

    public function get(string $pattern, array $handler): self
    {
        return $this->add('GET', $pattern, $handler);
    }

    public function post(string $pattern, array $handler): self
    {
        return $this->add('POST', $pattern, $handler);
    }

    public function add(string $method, string $pattern, array $handler): self
    {
        $this->routes[] = [strtoupper($method), $this->compile($pattern), $handler];

        return $this;
    }

    // [обработчик, параметры из пути] или null
    public function match(string $method, string $path): ?array
    {
        foreach ($this->routes as [$routeMethod, $regex, $handler]) {
            if ($routeMethod === $method && preg_match($regex, $path, $matches)) {
                return [$handler, array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY)];
            }
        }

        return null;
    }

    // /machines/{id}/buy -> #^/machines/(?P<id>[^/]+)/buy$#
    private function compile(string $pattern): string
    {
        $regex = preg_replace_callback('#\{(\w+)\}|[^{]+#', function (array $part) {
            return isset($part[1]) && $part[1] !== '' ? '(?P<' . $part[1] . '>[^/]+)' : preg_quote($part[0], '#');
        }, $pattern);

        return '#^' . $regex . '$#';
    }
}
