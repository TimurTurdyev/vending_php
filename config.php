<?php
return [
    'data' => __DIR__ . '/data',
    'state' => __DIR__ . '/storage/state.json',
    'templates' => __DIR__ . '/templates',
    'timezone' => 'Europe/Moscow',
    // optimal - наименьшее число монет с учетом запаса, greedy - самые крупные монеты по очереди
    'change' => 'optimal',
    // покупатель и аппарат консоли, если не указаны --as и --machine
    'cli' => ['customer' => 'ivan', 'machine' => 'coffee'],
];
