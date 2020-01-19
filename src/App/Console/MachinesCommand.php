<?php
declare(strict_types=1);

namespace App\Console;

use DateTimeImmutable;
use Framework\Console\Input;
use Framework\Console\Output;
use Vending\World;

final class MachinesCommand extends WorldCommand
{
    public function name(): string
    {
        return 'machines';
    }

    public function description(): string
    {
        return 'список аппаратов';
    }

    protected function handle(World $world, Input $input, Output $output, DateTimeImmutable $now): void
    {
        $customer = $this->customer($world, $input);
        $rows = [];

        foreach ($world->machines() as $machine) {
            $rows[] = [
                $machine->id(),
                $machine->info()->name(),
                $machine->info()->location(),
                $machine->currency()->code(),
                $machine->busyFor($customer) ? 'занят' : 'свободен',
            ];
        }

        $output->table(['ID', 'Аппарат', 'Где стоит', 'Валюта', 'Сейчас'], $rows);
    }
}
