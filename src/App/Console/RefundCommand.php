<?php
declare(strict_types=1);

namespace App\Console;

use Framework\Console\Output;
use Vending\Simulation;

final class RefundCommand extends VendingCommand
{
    public function name(): string
    {
        return 'refund';
    }

    public function description(): string
    {
        return 'вернуть внесенную сумму';
    }

    protected function handle(Simulation $simulation, array $arguments, Output $output): void
    {
        $output->line('Сдача: ' . $this->describe($simulation->refund()->change()));
    }
}
