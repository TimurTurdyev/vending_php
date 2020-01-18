<?php
declare(strict_types=1);

namespace App\Console;

use DateTimeImmutable;
use Framework\Console\Input;
use Framework\Console\Output;
use Vending\World;

final class RefundCommand extends WorldCommand
{
    public function name(): string
    {
        return 'refund';
    }

    public function description(): string
    {
        return 'вернуть внесенную сумму';
    }

    protected function handle(World $world, Input $input, Output $output, DateTimeImmutable $now): void
    {
        $machine = $this->machine($world, $input);
        $purchase = $machine->refund($this->customer($world, $input));

        $output->line('Сдача: ' . $this->describe($purchase->change(), $machine->currency()));
    }
}
