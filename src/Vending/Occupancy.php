<?php
declare(strict_types=1);

namespace Vending;

// кто сейчас стоит у аппарата
final class Occupancy
{
    /** @var string */
    private $customer;

    public function __construct(string $customer)
    {
        $this->customer = $customer;
    }

    public function customer(): string
    {
        return $this->customer;
    }
}
