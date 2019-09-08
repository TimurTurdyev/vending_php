<?php
return [
    'data' => __DIR__ . '/data',
    'templates' => __DIR__ . '/templates',
    // optimal - наименьшее число монет с учетом запаса, greedy - самые крупные монеты по очереди
    'change' => 'optimal',
    'cli_storage' => __DIR__ . '/storage/cli.json',
];
