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
        try {
            return $this->dispatch($request);
        } catch (HttpException $e) {
            return new Response($e->getMessage(), $e->status());
        }
    }

    private function dispatch(Request $request): Response
    {
        $match = $this->router->match($request->method(), $request->path());

        if ($match === null) {
            throw HttpException::notFound();
        }

        [[$controller, $action], $parameters] = $match;
        $request = $request->withAttributes($parameters);

        return $this->container->get($controller)->$action($request);
    }
}
