<?php
declare(strict_types=1);

namespace App\Console;

use DateTimeImmutable;
use Framework\Console\Input;
use Framework\Console\Output;
use Vending\World;

final class CollectCommand extends WorldCommand
{
    public function name(): string
    {
        return 'machine:collect';
    }

    public function description(): string
    {
        return 'инкассация: забрать выручку, оставив монеты на сдачу';
    }

    protected function handle(World $world, Input $input, Output $output, DateTimeImmutable $now): void
    {
        $machine = $this->machine($world, $input);

        $output->line('Забрано: ' . $this->describe($machine->collect(), $machine->currency()));
    }
}
