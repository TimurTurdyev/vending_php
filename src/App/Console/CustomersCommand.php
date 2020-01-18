<?php
declare(strict_types=1);

namespace App\Console;

use DateTimeImmutable;
use Framework\Console\Input;
use Framework\Console\Output;
use Vending\World;

final class CustomersCommand extends WorldCommand
{
    public function name(): string
    {
        return 'customers';
    }

    public function description(): string
    {
        return 'покупатели и их кошельки, выбрать покупателя: --as=maria';
    }

    protected function handle(World $world, Input $input, Output $output, DateTimeImmutable $now): void
    {
        $currencies = [];

        foreach ($world->machines() as $machine) {
            $currencies[$machine->currency()->code()] = $machine->currency();
        }

        $rows = [];

        foreach ($world->customers() as $customer) {
            $wallets = [];

            foreach ($customer->wallets() as $code => $coins) {
                if (isset($currencies[$code])) {
                    $wallets[] = $currencies[$code]->format($coins->total());
                }
            }

            $rows[] = [$customer->id(), $customer->name(), implode('; ', $wallets)];
        }

        $output->table(['ID', 'Имя', 'Кошельки'], $rows);
    }
}
