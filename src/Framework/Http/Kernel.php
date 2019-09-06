<?php
declare(strict_types=1);

namespace Framework\Http;

use Framework\Container;

final class Kernel
{
    /** @var Container */
    private $container;

    /** @var Router */
    private $router;

    public function __construct(Container $container, Router $router)
    {
        $this->container = $container;
        $this->router = $router;
    }

    public function handle(Request $request): Response
    {
        $handler = $this->router->match($request->method(), $request->path());

        if ($handler === null) {
            return new Response('Страница не найдена', 404);
        }

        [$controller, $action] = $handler;

        return $this->container->get($controller)->$action($request);
    }
}
