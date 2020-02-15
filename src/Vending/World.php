<?php
declare(strict_types=1);

namespace Vending;

use DateTimeImmutable;

// все аппараты, покупатели и владельцы, которые видят и веб, и консоль
final class World
{
    /** @var Machine[] */
    private $machines = [];

    /** @var Customer[] */
    private $customers = [];

    /** @var Operator[] */
    private $operators = [];

    public function __construct(array $machines, array $customers, array $operators = [])
    {
        foreach ($machines as $machine) {
            $this->machines[$machine->id()] = $machine;
        }

        foreach ($customers as $customer) {
            $this->customers[$customer->id()] = $customer;
        }

        foreach ($operators as $operator) {
            $this->operators[$operator->id()] = $operator;
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

    public function join(Customer $customer): void
    {
        $this->customers[$customer->id()] = $customer;
    }

    public function operator(string $id): ?Operator
    {
        return $this->operators[$id] ?? null;
    }

    public function releaseAbandoned(DateTimeImmutable $now): void
    {
        foreach ($this->machines as $machine) {
            $customer = $machine->abandonedBy($now);

            if ($customer !== null && $this->hasCustomer($customer)) {
                $machine->giveBack($this->customer($customer));
            }
        }
    }
}
