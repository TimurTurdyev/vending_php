<?php
declare(strict_types=1);

namespace App\Console;

use Framework\Console\Command;
use Framework\Console\Input;
use Framework\Console\Output;
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
        return 'вернуть все аппараты и покупателей к начальным данным';
    }

    public function run(Input $input, Output $output): int
    {
        $this->world->reset();
        $output->line('Все аппараты и покупатели сброшены');

        return 0;
    }
}
