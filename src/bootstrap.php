<?php
declare(strict_types=1);

use Framework\Clock\Clock;
use Framework\Clock\SystemClock;
use Framework\Container;
use Framework\Storage\JsonFile;
use Framework\View\View;
use Vending\Fixtures;
use Vending\Money\ChangeMaker;
use Vending\Money\GreedyChange;
use Vending\Money\OptimalChange;

require_once __DIR__ . '/autoload.php';

$container = new Container();

$container->set('config', function () {
    return require dirname(__DIR__) . '/config.php';
});

$container->set(Clock::class, function (Container $container) {
    return new SystemClock($container->get('config')['timezone']);
});

$container->set(JsonFile::class, function (Container $container) {
    return new JsonFile($container->get('config')['state']);
});

$container->set(Fixtures::class, function (Container $container) {
    return new Fixtures($container->get('config')['data']);
});

$container->set(ChangeMaker::class, function (Container $container) {
    return $container->get('config')['change'] === 'greedy' ? new GreedyChange() : new OptimalChange();
});

$container->set(View::class, function (Container $container) {
    return new View($container->get('config')['templates']);
});

return $container;
