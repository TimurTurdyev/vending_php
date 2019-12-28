<?php
declare(strict_types=1);

namespace App\Console;

use DateTimeImmutable;
use Framework\Console\Input;
use Framework\Console\Output;
use Vending\World;

final class RestockCommand extends WorldCommand
{
    public function name(): string
    {
        return 'machine:restock';
    }

    public function description(): string
    {
        return 'пополнить ячейку: machine:restock A1 [сколько], без числа - до полной';
    }

    protected function handle(World $world, Input $input, Output $output, DateTimeImmutable $now): void
    {
        $code = $this->argument($input, 0, 'machine:restock A1 [сколько]');
        $quantity = $input->argument(1);
        $added = $this->machine($world, $input)->restock($code, $quantity === null ? null : (int)$quantity);

        $output->line(sprintf('В ячейку %s добавлено %d шт.', strtoupper($code), $added));
    }
}
