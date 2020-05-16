<?php
declare(strict_types=1);

namespace App\Console;

use Framework\Console\Command;
use Framework\Console\Input;
use Framework\Console\Output;
use Vending\VendingException;
use Vending\WorldRepository;

final class ResetCommand implements Command
{
    /** @var WorldRepository */
    private $world;

    public function __construct(WorldRepository $world)
    {
        $this->world = $world;
    }

    public function name(): string
    {
        return 'reset';
    }

    public function description(): string
    {
        return 'начальные данные: reset coffee - один аппарат, reset - все и покупатели';
    }

    public function run(Input $input, Output $output): int
    {
        $machine = $input->argument(0);

        try {
            $this->world->reset($machine);
        } catch (VendingException $e) {
            $output->error($e->getMessage());

            return 1;
        }

        $output->line($machine === null ? 'Все аппараты и покупатели сброшены' : sprintf('Аппарат %s сброшен', $machine));

        return 0;
    }
}
