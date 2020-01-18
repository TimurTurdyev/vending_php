<?php
declare(strict_types=1);

namespace App\Console;

use DateTimeImmutable;
use Framework\Console\Input;
use Framework\Console\Output;
use Vending\World;

final class InsertCommand extends WorldCommand
{
    public function name(): string
    {
        return 'insert';
    }

    public function description(): string
    {
        return 'внести монеты: insert 10 5 0.50';
    }

    protected function handle(World $world, Input $input, Output $output, DateTimeImmutable $now): void
    {
        $this->argument($input, 0, 'insert 10 5 2');

        $machine = $this->machine($world, $input);
        $customer = $this->customer($world, $input);

        foreach ($input->arguments() as $coin) {
            $machine->insert($customer, $machine->currency()->parse($coin));
        }

        $output->line('Внесено: ' . $machine->currency()->format($machine->cash()->credit()));
    }
}
