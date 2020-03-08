<?php
declare(strict_types=1);

use App\Http\CustomerController;
use App\Http\MachineController;
use App\Http\OperatorController;
use Framework\Container;
use Framework\Http\Kernel;
use Framework\Http\Request;
use Framework\Http\Router;
use Framework\Storage\SessionStorage;
use Framework\Storage\Storage;

// встроенный сервер PHP сам отдает стили и скрипты
if (PHP_SAPI === 'cli-server' && strpos((string)parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/assets/') === 0) {
    return false;
}

/** @var Container $container */
$container = require __DIR__ . '/src/bootstrap.php';

$container->set(Storage::class, function () {
    return new SessionStorage();
});

$router = (new Router())
    ->get('/', [MachineController::class, 'index'])
    ->get('/machines/{id}', [MachineController::class, 'show'])
    ->post('/machines/{id}/insert', [MachineController::class, 'insert'])
    ->post('/machines/{id}/buy', [MachineController::class, 'buy'])
    ->post('/machines/{id}/refund', [MachineController::class, 'refund'])
    ->post('/customers/choose', [CustomerController::class, 'choose'])
    ->post('/customers/new', [CustomerController::class, 'create'])
    ->get('/operator/login', [OperatorController::class, 'loginForm'])
    ->post('/operator/login', [OperatorController::class, 'login'])
    ->post('/operator/logout', [OperatorController::class, 'logout'])
    ->get('/operator', [OperatorController::class, 'dashboard'])
    ->get('/operator/machines/{id}', [OperatorController::class, 'machine'])
    ->post('/operator/machines/{id}/restock', [OperatorController::class, 'restock'])
    ->post('/operator/machines/{id}/collect', [OperatorController::class, 'collect']);

(new Kernel($container, $router))->handle(Request::fromGlobals())->send();
