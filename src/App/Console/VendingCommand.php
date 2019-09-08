<?php
declare(strict_types=1);

namespace App\Console;

use Framework\Console\Command;
use Framework\Console\Output;
use InvalidArgumentException;
use Vending\Coins;
use Vending\Simulation;
use Vending\SimulationRepository;
use Vending\VendingException;

// загружает аппарат, выполняет команду и сохраняет состояние, только если она прошла без ошибок
abstract class VendingCommand implements Command
{
    /** @var SimulationRepository */
    private $repository;

    public function __construct(SimulationRepository $repository)
    {
        $this->repository = $repository;
    }

    final public function run(array $arguments, Output $output): int
    {
        $simulation = $this->repository->load();

        try {
            $this->handle($simulation, $arguments, $output);
        } catch (VendingException | InvalidArgumentException $e) {
            $output->error($e->getMessage());

            return 1;
        }

        $this->repository->save($simulation);

        return 0;
    }

    abstract protected function handle(Simulation $simulation, array $arguments, Output $output): void;

    protected function describe(Coins $coins): string
    {
        if ($coins->isEmpty()) {
            return 'нет';
        }

        $parts = [];

        foreach ($coins as $value => $count) {
            if ($count > 0) {
                $parts[] = sprintf('%d руб. x %d', $value, $count);
            }
        }

        return sprintf('%s = %d руб.', implode(', ', $parts), $coins->total());
    }
}
