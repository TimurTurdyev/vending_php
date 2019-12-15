<?php
declare(strict_types=1);

namespace Vending;

use Vending\Catalog\Product;
use Vending\Catalog\Slot;
use Vending\Money\CashBox;
use Vending\Money\ChangeMaker;
use Vending\Money\Coins;
use Vending\Money\Currency;
use Vending\Sales\Journal;
use Vending\Sales\Sale;

// аппарат и покупатель <-> массивы в формате data/*.json, суммы в копейках и центах
final class Mapper
{
    /** @var ChangeMaker */
    private $changeMaker;

    public function __construct(ChangeMaker $changeMaker)
    {
        $this->changeMaker = $changeMaker;
    }

    public function machine(array $data): Machine
    {
        return new Machine(
            new MachineInfo(
                $data['id'],
                $data['name'],
                $data['location'] ?? ''
            ),
            array_map([Product::class, 'fromArray'], $data['catalog']),
            array_map([Slot::class, 'fromArray'], $data['slots']),
            new CashBox(
                Currency::fromArray($data['currency']),
                new Coins($data['cash']['coins']),
                (int)($data['cash']['credit'] ?? 0),
                $this->changeMaker
            ),
            new Journal(array_map([Sale::class, 'fromArray'], $data['sales'] ?? []))
        );
    }

    public function machineToArray(Machine $machine): array
    {
        $info = $machine->info();
        $cash = $machine->cash();

        return [
            'id' => $info->id(),
            'name' => $info->name(),
            'location' => $info->location(),
            'currency' => $machine->currency()->toArray(),
            'cash' => ['coins' => $cash->coins()->toArray(), 'credit' => $cash->credit()],
            'catalog' => array_values(array_map(function (Product $product) {
                return $product->toArray();
            }, $machine->products())),
            'slots' => array_values(array_map(function (Slot $slot) {
                return $slot->toArray();
            }, $machine->slots())),
            'sales' => array_map(function (Sale $sale) {
                return $sale->toArray();
            }, $machine->journal()->all()),
        ];
    }

    public function customer(array $data): Customer
    {
        return new Customer($data['id'], $data['name'], $data['wallets'] ?? []);
    }

    public function customerToArray(Customer $customer): array
    {
        return [
            'id' => $customer->id(),
            'name' => $customer->name(),
            'wallets' => array_map(function (Coins $coins) {
                return $coins->toArray();
            }, $customer->wallets()),
        ];
    }
}
