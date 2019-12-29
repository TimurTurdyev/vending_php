<?php
declare(strict_types=1);

namespace App\Console;

use DateTimeImmutable;
use Framework\Console\Input;
use Framework\Console\Output;
use Vending\World;

final class ReportCommand extends WorldCommand
{
    public function name(): string
    {
        return 'machine:report';
    }

    public function description(): string
    {
        return 'продажи аппарата по товарам';
    }

    protected function handle(World $world, Input $input, Output $output, DateTimeImmutable $now): void
    {
        $machine = $this->machine($world, $input);
        $currency = $machine->currency();
        $report = $machine->journal()->report();

        $rows = [];

        foreach ($report->byProduct() as $name => $row) {
            $rows[] = [$name, $row['count'], $currency->format($row['revenue'])];
        }

        $output->table(['Товар', 'Продано', 'Выручка'], $rows);

        $output->line(sprintf('Всего: %d продаж на %s', $report->count(), $currency->format($report->revenue())));
    }
}
