<?php
declare(strict_types=1);

namespace Vending\Sales;

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
}
