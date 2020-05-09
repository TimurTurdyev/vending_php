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
        return 'продажи аппарата: machine:report --from=2020-05-01 --to=2020-05-31';
    }

    protected function handle(World $world, Input $input, Output $output, DateTimeImmutable $now): void
    {
        $machine = $this->machine($world, $input);
        $currency = $machine->currency();
        $report = $machine->journal()->report()->between($input->option('from'), $input->option('to'));

        $rows = [];

        foreach ($report->byProduct() as $name => $row) {
            $rows[] = [$name, $row['count'], $currency->format($row['revenue'])];
        }

        $output->table(['Товар', 'Продано', 'Выручка'], $rows);

        if ($input->option('days')) {
            $rows = [];

            foreach ($report->byDay() as $day => $row) {
                $rows[] = [$day, $row['count'], $currency->format($row['revenue'])];
            }

            $output->table(['День', 'Продано', 'Выручка'], $rows);
        }

        $output->line(sprintf('Всего: %d продаж на %s', $report->count(), $currency->format($report->revenue())));
    }
}
