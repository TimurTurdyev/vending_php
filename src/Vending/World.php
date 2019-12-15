<?php
declare(strict_types=1);

namespace Vending;

// все аппараты, покупатели и владельцы, которые видят и веб, и консоль
final class World
{
    /** @var Machine[] */
    private $machines = [];

    /** @var Customer[] */
    private $customers = [];

    public function __construct(array $machines, array $customers)
    {
        foreach ($machines as $machine) {
            $this->machines[$machine->id()] = $machine;
        }

        foreach ($customers as $customer) {
            $this->customers[$customer->id()] = $customer;
        }
    }

    /** @return Machine[] */
    public function machines(): array
    {
        return $this->machines;
    }

    public function hasMachine(string $id): bool
    {
        return isset($this->machines[$id]);
    }

    public function machine(string $id): Machine
    {
        if (!$this->hasMachine($id)) {
            throw VendingException::unknownMachine($id);
        }

        return $this->machines[$id];
    }

    /** @return Customer[] */
    public function customers(): array
    {
        return $this->customers;
    }

    public function hasCustomer(string $id): bool
    {
        return isset($this->customers[$id]);
    }

    public function customer(string $id): Customer
    {
        if (!$this->hasCustomer($id)) {
            throw VendingException::unknownCustomer($id);
        }

        return $this->customers[$id];
    }
}
