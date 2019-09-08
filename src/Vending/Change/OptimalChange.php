<?php
declare(strict_types=1);

namespace Vending\Change;

use Vending\Coins;

// наименьшее число монет с учетом запаса: динамика по суммам, номиналы добавляются по одному
final class OptimalChange implements ChangeMaker
{
    public function make(int $amount, Coins $available): ?Coins
    {
        $values = $available->denominations();
        $best = [0 => 0];
        $taken = [];

        foreach ($values as $i => $value) {
            $limit = $available->of($value);
            $next = [];

            foreach ($best as $sum => $coins) {
                for ($k = 0; $k <= $limit && $sum + $k * $value <= $amount; $k++) {
                    $total = $sum + $k * $value;

                    if (!isset($next[$total]) || $coins + $k < $next[$total]) {
                        $next[$total] = $coins + $k;
                        $taken[$i][$total] = $k;
                    }
                }
            }

            $best = $next;
        }

        if (!isset($best[$amount])) {
            return null;
        }

        $change = [];

        for ($i = count($values) - 1, $rest = $amount; $i >= 0; $i--) {
            $k = $taken[$i][$rest];

            if ($k > 0) {
                $change[$values[$i]] = $k;
                $rest -= $k * $values[$i];
            }
        }

        return new Coins($change);
    }
}
