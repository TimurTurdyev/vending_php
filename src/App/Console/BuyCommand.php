<?php
declare(strict_types=1);

namespace App\Console;

use Framework\Console\Output;
use InvalidArgumentException;
use Vending\Simulation;

final class BuyCommand extends VendingCommand
{
    public function name(): string
    {
        return 'buy';
    }

    public function description(): string
    {
        return 'купить напиток по номеру: buy 2';
    }

    protected function handle(Simulation $simulation, array $arguments, Output $output): void
    {
        if (!isset($arguments[0])) {
            throw new InvalidArgumentException('укажите номер напитка, например: buy 2');
        }

        $purchase = $simulation->buy((int)$arguments[0]);
        $product = $purchase->product();

        $output->line(sprintf('Ваш напиток: %s за %d руб.', $product->name(), $product->price()));
        $output->line('Сдача: ' . $this->describe($purchase->change()));
    }
}
