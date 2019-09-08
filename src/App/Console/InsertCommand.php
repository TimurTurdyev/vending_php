<?php
declare(strict_types=1);

namespace App\Console;

use Framework\Console\Output;
use InvalidArgumentException;
use Vending\Simulation;

final class InsertCommand extends VendingCommand
{
    public function name(): string
    {
        return 'insert';
    }

    public function description(): string
    {
        return 'внести монеты: insert 10 5 2';
    }

    protected function handle(Simulation $simulation, array $arguments, Output $output): void
    {
        if (!$arguments) {
            throw new InvalidArgumentException('укажите монеты, например: insert 10 5 2');
        }

        foreach ($arguments as $coin) {
            $simulation->insert((int)$coin);
        }

        $output->line(sprintf('Внесено: %d руб.', $simulation->machine()->credit()));
    }
}
