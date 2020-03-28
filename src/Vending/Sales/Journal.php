<?php
declare(strict_types=1);

namespace Vending\Sales;

use DateTimeImmutable;

final class Journal
{
    /** @var Sale[] */
    private $sales;

    public function __construct(array $sales = [])
    {
        $this->sales = $sales;
    }

    public function record(Sale $sale): void
    {
        $this->sales[] = $sale;
    }

    /** @return Sale[] */
    public function all(): array
    {
        return $this->sales;
    }

    /** @return Sale[] последние продажи, новые сверху */
    public function latest(int $limit): array
    {
        return array_reverse(array_slice($this->sales, -$limit));
    }

    public function soldCount(string $sku): int
    {
        return count(array_filter($this->sales, function (Sale $sale) use ($sku) {
            return $sale->sku() === $sku;
        }));
    }

    public function customerCount(string $customer, DateTimeImmutable $day): int
    {
        return count(array_filter($this->sales, function (Sale $sale) use ($customer, $day) {
            return $sale->customer() === $customer && $sale->day() === $day->format('Y-m-d');
        }));
    }

    public function report(): Report
    {
        return new Report($this->sales);
    }
}
