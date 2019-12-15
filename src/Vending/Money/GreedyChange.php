<?php
declare(strict_types=1);

namespace Vending\Money;

// берет самые крупные монеты; при неполном запасе может не найти сдачу, которая есть
final class GreedyChange implements ChangeMaker
{
    public function make(int $amount, Coins $available): ?Coins
    {
        $change = [];

        foreach ($available as $value => $count) {
            $take = min($count, intdiv($amount, $value));

            if ($take > 0) {
                $change[$value] = $take;
                $amount -= $take * $value;
            }
        }

        return $amount === 0 ? new Coins($change) : null;
    }
}
