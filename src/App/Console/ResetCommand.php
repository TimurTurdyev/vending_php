<?php
declare(strict_types=1);

namespace App\Console;

use Framework\Console\Command;
use Framework\Console\Output;
use Vending\SimulationRepository;

final class ResetCommand implements Command
{
    /** @var SimulationRepository */
    private $repository;

    public function __construct(SimulationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function name(): string
    {
        return 'reset';
    }

    public function description(): string
    {
        return 'вернуть аппарат и кошелек к начальным данным';
    }

    public function run(array $arguments, Output $output): int
    {
        $this->repository->reset();
        $output->line('Аппарат сброшен к начальным данным');

        return 0;
    }
}
