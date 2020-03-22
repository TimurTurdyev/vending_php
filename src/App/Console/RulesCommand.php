<?php
declare(strict_types=1);

namespace App\Console;

use DateTimeImmutable;
use Framework\Console\Input;
use Framework\Console\Output;
use InvalidArgumentException;
use Vending\World;

final class RulesCommand extends WorldCommand
{
    public function name(): string
    {
        return 'machine:rules';
    }

    public function description(): string
    {
        return 'правила аппарата, включить или выключить: machine:rules morning off';
    }

    protected function handle(World $world, Input $input, Output $output, DateTimeImmutable $now): void
    {
        $rules = $this->machine($world, $input)->rules();
        $id = $input->argument(0);

        if ($id !== null) {
            $state = $input->argument(1);

            if (!in_array($state, ['on', 'off'], true)) {
                throw new InvalidArgumentException('использование: machine:rules morning on|off');
            }

            $rules->toggle($id, $state === 'on');
        }

        $rows = [];

        foreach ($rules->all() as $rule) {
            $rows[] = [$rule->id(), $rule->label(), $rules->enabled($rule->id()) ? 'вкл' : 'выкл'];
        }

        $output->table(['ID', 'Правило', ''], $rows);
    }
}
