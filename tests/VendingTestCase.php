<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Vending\Customer;
use Vending\Machine;
use Vending\Mapper;
use Vending\Money\OptimalChange;
use Vending\Rules\RuleFactory;

abstract class VendingTestCase extends TestCase
{
    // аппарат в формате data/machines/*.json, суммы в копейках
    protected function machine(array $overrides = []): Machine
    {
        $mapper = new Mapper(new OptimalChange(), new RuleFactory());

        return $mapper->machine($overrides + [
            'id' => 'test',
            'name' => 'Тестовый аппарат',
            'currency' => ['code' => 'RUB', 'symbol' => 'руб.', 'digits' => 2],
            'cash' => ['coins' => [100 => 10, 200 => 10, 500 => 10, 1000 => 10]],
            'catalog' => [
                ['sku' => 'tea', 'name' => 'Чай', 'category' => 'hot', 'price' => 1300],
                ['sku' => 'juice', 'name' => 'Сок', 'category' => 'cold', 'price' => 3500],
                ['sku' => 'energy', 'name' => 'Энергетик', 'category' => 'cold', 'price' => 8900, 'adult' => true],
            ],
            'slots' => [
                ['code' => 'A1', 'capacity' => 10, 'sku' => 'tea', 'quantity' => 5],
                ['code' => 'A2', 'capacity' => 10, 'sku' => 'juice', 'quantity' => 1],
                ['code' => 'A3', 'capacity' => 10, 'sku' => 'energy', 'quantity' => 5],
            ],
            'rules' => [],
        ]);
    }

    protected function customer(string $id = 'ivan', array $coins = [100 => 10, 200 => 10, 500 => 10, 1000 => 10]): Customer
    {
        return new Customer($id, ucfirst($id), ['RUB' => $coins]);
    }

    protected function time(string $time): DateTimeImmutable
    {
        return new DateTimeImmutable('2020-01-06 ' . $time, new DateTimeZone('Europe/Moscow'));
    }

    protected function insert(Machine $machine, Customer $customer, array $coins, string $time = '12:00'): void
    {
        foreach ($coins as $coin) {
            $machine->insert($customer, $coin, $this->time($time));
        }
    }
}
