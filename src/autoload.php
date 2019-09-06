<?php
declare(strict_types=1);

require_once __DIR__ . '/Framework/Autoloader.php';

(new Framework\Autoloader())
    ->addNamespace('Framework', __DIR__ . '/Framework')
    ->addNamespace('Vending', __DIR__ . '/Vending')
    ->register();
