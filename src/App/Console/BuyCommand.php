<?php
declare(strict_types=1);

namespace App\Console;

use DateTimeImmutable;
use Framework\Console\Input;
use Framework\Console\Output;
use Vending\World;

final class BuyCommand extends WorldCommand
{
    public function name(): string
    {
        return 'buy';
    }

    public function description(): string
    {
        return 'купить товар из ячейки: buy A2';
    }

    protected function handle(World $world, Input $input, Output $output, DateTimeImmutable $now): void
    {
        $machine = $this->machine($world, $input);
        $currency = $machine->currency();

        $purchase = $machine->buy($this->customer($world), $this->argument($input, 0, 'buy A2'), $now);

        $output->line(sprintf('Выдано: %s за %s', $purchase->product()->name(), $currency->format($purchase->product()->price())));

        $output->line('Сдача: ' . $this->describe($purchase->change(), $currency));
    }
}
