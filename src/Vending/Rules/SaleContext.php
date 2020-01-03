<?php
declare(strict_types=1);

namespace Vending\Rules;

use DateTimeImmutable;
use Vending\Catalog\Product;

// все, что правила знают о продаже
final class SaleContext
{
    /** @var Product */
    private $product;

    /** @var string */
    private $customer;

    /** @var DateTimeImmutable */
    private $time;

    /** @var int сколько таких товаров аппарат уже продал */
    private $sold;

    public function __construct(
        Product $product,
        string $customer,
        DateTimeImmutable $time,
        int $sold = 0
    ) {
        $this->product = $product;
        $this->customer = $customer;
        $this->time = $time;
        $this->sold = $sold;
    }

    public function product(): Product
    {
        return $this->product;
    }

    public function customer(): string
    {
        return $this->customer;
    }

    public function time(): DateTimeImmutable
    {
        return $this->time;
    }

    public function sold(): int
    {
        return $this->sold;
    }
}
