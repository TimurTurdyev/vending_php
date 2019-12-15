<?php
declare(strict_types=1);

namespace App\Console;

use DateTimeImmutable;
use Framework\Console\Input;
use Framework\Console\Output;
use Vending\World;

final class StatusCommand extends WorldCommand
{
    public function name(): string
    {
        return 'status';
    }

    public function description(): string
    {
        return 'витрина аппарата и ваш кошелек: status --machine=coffee';
    }

    protected function handle(World $world, Input $input, Output $output, DateTimeImmutable $now): void
    {
        $machine = $this->machine($world, $input);
        $customer = $this->customer($world);
        $currency = $machine->currency();

        $output->line(sprintf('%s (%s), покупатель: %s', $machine->info()->name(), $machine->info()->location(), $customer->name()));

        $rows = [];

        foreach ($machine->slots() as $slot) {
            if ($slot->sku() === null) {
                $rows[] = [$slot->code(), '-', '', ''];
                continue;
            }

            $product = $machine->product($slot->sku());
            $rows[] = [$slot->code(), $product->name(), $currency->format($product->price()), $slot->quantity()];
        }

        $output->table(['Ячейка', 'Товар', 'Цена', 'Осталось'], $rows);

        $output->line('Внесено: ' . $currency->format($machine->cash()->credit()));

        $output->line('Кошелек: ' . $this->describe($customer->wallet($currency), $currency));
    }
}
