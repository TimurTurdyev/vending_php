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
        return 'витрина аппарата и ваш кошелек: status --machine=coffee --as=ivan';
    }

    protected function handle(World $world, Input $input, Output $output, DateTimeImmutable $now): void
    {
        $machine = $this->machine($world, $input);
        $customer = $this->customer($world, $input);
        $currency = $machine->currency();

        $output->line(sprintf('%s (%s), покупатель: %s', $machine->info()->name(), $machine->info()->location(), $customer->name()));

        $rows = [];

        foreach ($machine->slots() as $slot) {
            $quote = $machine->quote($slot, $customer, $now);

            if ($quote === null) {
                $rows[] = [$slot->code(), '-', '', ''];
                continue;
            }

            $price = $currency->format($quote->price());

            if ($quote->changed()) {
                $price .= ' (было ' . $currency->format($quote->base()) . ')';
            }

            $product = $machine->product($slot->sku());
            $rows[] = [$slot->code(), $product->name() . ($product->adult() ? ' 18+' : ''), $price, $slot->quantity()];
        }

        $output->table(['Ячейка', 'Товар', 'Цена', 'Осталось'], $rows);

        foreach ($machine->rules()->all() as $rule) {
            if ($machine->rules()->enabled($rule->id())) {
                $output->line('* ' . $rule->label());
            }
        }

        $output->line('Внесено: ' . $currency->format($machine->cash()->credit()));

        if ($machine->cash()->exactChangeOnly()) {
            $output->line('Мало мелочи: только без сдачи');
        }

        $output->line('Кошелек: ' . $this->describe($customer->wallet($currency), $currency));
    }
}
