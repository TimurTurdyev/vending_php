<?php
declare(strict_types=1);

namespace Vending\Change;

use Vending\Coins;

interface ChangeMaker
{
    // null, если сумму нельзя набрать из доступных монет
    public function make(int $amount, Coins $available): ?Coins;
}
