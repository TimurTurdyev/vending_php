<?php
declare(strict_types=1);

namespace Vending;

use Framework\Clock\Clock;
use Framework\Storage\JsonFile;

final class WorldRepository
{
    /** @var JsonFile */
    private $file;

    /** @var Fixtures */
    private $fixtures;

    /** @var Mapper */
    private $mapper;

    /** @var Clock */
    private $clock;

    public function __construct(JsonFile $file, Fixtures $fixtures, Mapper $mapper, Clock $clock)
    {
        $this->file = $file;
        $this->fixtures = $fixtures;
        $this->mapper = $mapper;
        $this->clock = $clock;
    }

    public function read(): World
    {
        return $this->build($this->file->read() ?? $this->initial());
    }

    // изменения идут под блокировкой файла: два покупателя не потратят одну монету дважды
    public function transaction(callable $action)
    {
        return $this->file->update(function (?array &$data) use ($action) {
            $world = $this->build($data ?? $this->initial());
            $world->releaseAbandoned($this->clock->now());

            $result = $action($world);
            $data = $this->export($world);

            return $result;
        });
    }

    public function createGuest(): string
    {
        return $this->transaction(function (World $world) {
            $id = 'guest-' . bin2hex(random_bytes(3));
            $world->join($this->mapper->customer([
                'id' => $id,
                'name' => 'Гость ' . strtoupper(substr($id, 6)),
                'wallets' => $this->fixtures->guestWallets(),
            ]));

            return $id;
        });
    }

    // покупатель из консоли: --as=имя создает его с кошельком гостя
    public function ensureCustomer(string $id): void
    {
        $this->transaction(function (World $world) use ($id) {
            if (!$world->hasCustomer($id)) {
                $world->join($this->mapper->customer(['id' => $id, 'name' => $id, 'wallets' => $this->fixtures->guestWallets()]));
            }
        });
    }

    public function reset(): void
    {
        $this->file->delete();
    }

    private function initial(): array
    {
        $customers = [];

        foreach ($this->fixtures->customers() as $customer) {
            $customers[$customer['id']] = $customer;
        }

        return ['machines' => $this->fixtures->machines(), 'customers' => $customers];
    }

    private function build(array $data): World
    {
        return new World(
            array_map([$this->mapper, 'machine'], $data['machines']),
            array_map([$this->mapper, 'customer'], $data['customers'])
        );
    }

    private function export(World $world): array
    {
        return [
            'machines' => array_map([$this->mapper, 'machineToArray'], $world->machines()),
            'customers' => array_map([$this->mapper, 'customerToArray'], $world->customers()),
        ];
    }
}
