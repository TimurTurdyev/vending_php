<?php
declare(strict_types=1);

namespace App\Console;

use Framework\Console\Output;
use Vending\Simulation;

final class StatusCommand extends VendingCommand
{
    public function name(): string
    {
        return 'status';
    }

    public function description(): string
    {
        return 'напитки, монеты в кошельке и в аппарате';
    }

    protected function handle(Simulation $simulation, array $arguments, Output $output): void
    {
        $machine = $simulation->machine();
        $wallet = $simulation->wallet();
        $bank = $machine->bank();

        $products = [];

        foreach ($machine->products() as $product) {
            $products[] = [$product->id(), $product->name(), $product->price() . ' руб.', $product->amount()];
        }

        $output->table(['N', 'Напиток', 'Цена', 'Остаток'], $products);

        $coins = [];
        $values = array_unique(array_merge($wallet->denominations(), $bank->denominations()));
        rsort($values);

        foreach ($values as $value) {
            $coins[] = [$value . ' руб.', $wallet->of($value), $bank->of($value)];
        }

        $coins[] = ['Итого', $wallet->total() . ' руб.', $bank->total() . ' руб.'];

        $output->table(['Монета', 'Кошелек', 'Аппарат'], $coins);
        $output->line(sprintf('Внесено: %d руб.', $machine->credit()));
    }
}
