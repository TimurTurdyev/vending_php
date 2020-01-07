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

    /** @var int сколько покупок сделал этот покупатель сегодня */
    private $customerToday;

    /** @var bool */
    private $ageConfirmed;

    public function __construct(
        Product $product,
        string $customer,
        DateTimeImmutable $time,
        int $sold = 0,
        int $customerToday = 0,
        bool $ageConfirmed = false
    ) {
        $this->product = $product;
        $this->customer = $customer;
        $this->time = $time;
        $this->sold = $sold;
        $this->customerToday = $customerToday;
        $this->ageConfirmed = $ageConfirmed;
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

    public function customerToday(): int
    {
        return $this->customerToday;
    }

    public function ageConfirmed(): bool
    {
        return $this->ageConfirmed;
    }
}
