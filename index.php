<?php
declare(strict_types=1);

use App\Http\VendingController;
use Framework\Container;
use Framework\Http\Kernel;
use Framework\Http\Request;
use Framework\Http\Router;

/** @var Container $container */
$container = require __DIR__ . '/src/bootstrap.php';

$router = (new Router())
    ->get('/', [VendingController::class, 'index']);

(new Kernel($container, $router))->handle(Request::fromGlobals())->send();
