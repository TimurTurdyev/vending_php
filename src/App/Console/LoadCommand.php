<?php
declare(strict_types=1);

namespace App\Console;

use DateTimeImmutable;
use Framework\Console\Input;
use Framework\Console\Output;
use Vending\World;

final class LoadCommand extends WorldCommand
{
    public function name(): string
    {
        return 'machine:load';
    }

    public function description(): string
    {
        return 'загрузить в ячейку другой товар из каталога: machine:load B3 cocoa 15';
    }

    protected function handle(World $world, Input $input, Output $output, DateTimeImmutable $now): void
    {
        $usage = 'machine:load B3 cocoa 15';
        $machine = $this->machine($world, $input);
        $code = $this->argument($input, 0, $usage);
        $product = $machine->product($this->argument($input, 1, $usage));

        $machine->load($code, $product->sku(), (int)$this->argument($input, 2, $usage));

        $output->line(sprintf('В ячейке %s теперь %s', strtoupper($code), $product->name()));
    }
}
