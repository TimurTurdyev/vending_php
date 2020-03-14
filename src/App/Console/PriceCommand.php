<?php
declare(strict_types=1);

namespace App\Console;

use DateTimeImmutable;
use Framework\Console\Input;
use Framework\Console\Output;
use Vending\World;

final class PriceCommand extends WorldCommand
{
    public function name(): string
    {
        return 'machine:price';
    }

    public function description(): string
    {
        return 'поменять цену товара: machine:price coffee 19.50';
    }

    protected function handle(World $world, Input $input, Output $output, DateTimeImmutable $now): void
    {
        $machine = $this->machine($world, $input);
        $product = $machine->product($this->argument($input, 0, 'machine:price coffee 19.50'));
        $price = $machine->currency()->parse($this->argument($input, 1, 'machine:price coffee 19.50'));

        $machine->setPrice($product->sku(), $price);

        $output->line(sprintf('%s теперь стоит %s', $product->name(), $machine->currency()->format($price)));
    }
}
