<?php
declare(strict_types=1);

namespace Framework\Console;

final class Application
{
    /** @var string */
    private $name;

    /** @var Output */
    private $output;

    /** @var Command[] */
    private $commands = [];

    public function __construct(string $name, Output $output)
    {
        $this->name = $name;
        $this->output = $output;
    }

    public function add(Command $command): self
    {
        $this->commands[$command->name()] = $command;

        return $this;
    }

    public function run(Input $input): int
    {
        $name = $input->argument(0, 'help');

        if (isset($this->commands[$name])) {
            return $this->commands[$name]->run($input->shift(), $this->output);
        }

        if ($name !== 'help') {
            $this->output->error(sprintf('неизвестная команда "%s"', $name));
        }

        $this->help();

        return $name === 'help' ? 0 : 1;
    }

    private function help(): void
    {
        $this->output->line($this->name);
        $this->output->line();
        $this->output->line('Команды:');

        $width = max(array_merge([4], array_map('strlen', array_keys($this->commands))));

        foreach ($this->commands as $name => $command) {
            $this->output->line(sprintf('  %-' . $width . 's  %s', $name, $command->description()));
        }
    }
}
