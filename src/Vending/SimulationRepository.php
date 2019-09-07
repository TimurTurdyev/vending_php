<?php
declare(strict_types=1);

namespace Vending;

use Framework\Storage\Storage;
use Vending\Change\ChangeMaker;

final class SimulationRepository
{
    private const KEY = 'vending';

    /** @var Storage */
    private $storage;

    /** @var JsonFixtures */
    private $fixtures;

    /** @var ChangeMaker */
    private $changeMaker;

    public function __construct(Storage $storage, JsonFixtures $fixtures, ChangeMaker $changeMaker)
    {
        $this->storage = $storage;
        $this->fixtures = $fixtures;
        $this->changeMaker = $changeMaker;
    }

    public function load(): Simulation
    {
        $state = $this->storage->get(self::KEY);

        if (!is_array($state)) {
            return new Simulation(
                new Machine($this->fixtures->products(), $this->fixtures->machine(), 0, $this->changeMaker),
                $this->fixtures->wallet()
            );
        }

        return new Simulation(
            new Machine(
                array_map([Product::class, 'fromArray'], $state['products']),
                new Coins($state['bank']),
                (int)$state['credit'],
                $this->changeMaker
            ),
            new Coins($state['wallet'])
        );
    }

    public function save(Simulation $simulation): void
    {
        $machine = $simulation->machine();

        $this->storage->set(self::KEY, [
            'wallet' => $simulation->wallet()->toArray(),
            'bank' => $machine->bank()->toArray(),
            'credit' => $machine->credit(),
            'products' => array_values(array_map(function (Product $product) {
                return $product->toArray();
            }, $machine->products())),
        ]);
    }
}
