<?php
declare(strict_types=1);

use Framework\Container;
use Framework\View\View;
use Vending\Change\ChangeMaker;
use Vending\Change\GreedyChange;
use Vending\Change\OptimalChange;
use Vending\JsonFixtures;

require_once __DIR__ . '/autoload.php';

$container = new Container();

$container->set('config', function () {
    return require dirname(__DIR__) . '/config.php';
});

$container->set(JsonFixtures::class, function (Container $container) {
    return new JsonFixtures($container->get('config')['data']);
});

$container->set(ChangeMaker::class, function (Container $container) {
    return $container->get('config')['change'] === 'greedy' ? new GreedyChange() : new OptimalChange();
});

$container->set(View::class, function (Container $container) {
    return new View($container->get('config')['templates']);
});

return $container;
