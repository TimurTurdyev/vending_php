<?php
declare(strict_types=1);

namespace Vending\Sales;

final class Report
{
    /** @var Sale[] */
    private $sales;

    public function __construct(array $sales)
    {
        $this->sales = $sales;
    }

    public function count(): int
    {
        return count($this->sales);
    }

    public function revenue(): int
    {
        return array_sum(array_map(function (Sale $sale) {
            return $sale->price();
        }, $this->sales));
    }

    /** @return array[] название => [count, revenue], самые доходные сверху */
    public function byProduct(): array
    {
        return $this->group(function (Sale $sale) {
            return $sale->name();
        });
    }

    private function group(callable $key): array
    {
        $groups = [];

        foreach ($this->sales as $sale) {
            $name = $key($sale);
            $groups[$name] = $groups[$name] ?? ['count' => 0, 'revenue' => 0];
            $groups[$name]['count']++;
            $groups[$name]['revenue'] += $sale->price();
        }

        uasort($groups, function (array $a, array $b) {
            return $b['revenue'] <=> $a['revenue'];
        });

        return $groups;
    }
}
