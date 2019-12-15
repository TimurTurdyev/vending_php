<?php
declare(strict_types=1);

namespace Vending;

use Framework\Storage\JsonFile;

final class WorldRepository
{
    /** @var JsonFile */
    private $file;

    /** @var Fixtures */
    private $fixtures;

    /** @var Mapper */
    private $mapper;

    public function __construct(JsonFile $file, Fixtures $fixtures, Mapper $mapper)
    {
        $this->file = $file;
        $this->fixtures = $fixtures;
        $this->mapper = $mapper;
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
            $result = $action($world);
            $data = $this->export($world);

            return $result;
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
