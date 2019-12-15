<?php
declare(strict_types=1);

namespace App\Console;

final class Defaults
{
    /** @var string */
    private $customer;

    /** @var string */
    private $machine;

    public function __construct(string $customer, string $machine)
    {
        $this->customer = $customer;
        $this->machine = $machine;
    }

    public function customer(): string
    {
        return $this->customer;
    }

    public function machine(): string
    {
        return $this->machine;
    }
}
