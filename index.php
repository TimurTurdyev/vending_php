<?php
declare(strict_types=1);

use App\Http\VendingController;
use Framework\Container;
use Framework\Http\Kernel;
use Framework\Http\Request;
use Framework\Http\Router;
use Framework\Storage\SessionStorage;
use Framework\Storage\Storage;

/** @var Container $container */
$container = require __DIR__ . '/src/bootstrap.php';

$container->set(Storage::class, function () {
    return new SessionStorage();
});

$router = (new Router())
    ->get('/', [VendingController::class, 'index'])
    ->post('/insert', [VendingController::class, 'insert'])
    ->post('/buy', [VendingController::class, 'buy'])
    ->post('/refund', [VendingController::class, 'refund']);

(new Kernel($container, $router))->handle(Request::fromGlobals())->send();
