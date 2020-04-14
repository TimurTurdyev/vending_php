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

    /** @var callable[] */
    private $guards = [];

    /** @var callable|null */
    private $errorHandler;

    public function __construct(Container $container, Router $router)
    {
        $this->container = $container;
        $this->router = $router;
    }

    // проверка перед любым контроллером, при отказе бросает HttpException
    public function guard(callable $guard): self
    {
        $this->guards[] = $guard;

        return $this;
    }

    public function onError(callable $handler): self
    {
        $this->errorHandler = $handler;

        return $this;
    }

    public function handle(Request $request): Response
    {
        try {
            return $this->dispatch($request);
        } catch (HttpException $e) {
            if ($this->errorHandler !== null) {
                return ($this->errorHandler)($e, $request);
            }

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

        foreach ($this->guards as $guard) {
            $guard($request);
        }

        return $this->container->get($controller)->$action($request);
    }
}
