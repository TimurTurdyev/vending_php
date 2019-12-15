<?php
declare(strict_types=1);

namespace App\Console;

use Framework\Console\Application;
use Framework\Console\Command;
use Framework\Console\Input;
use Framework\Console\Output;

final class ShellCommand implements Command
{
    private const PROMPT = 'vending> ';

    /** @var Application */
    private $application;

    /** @var resource|null */
    private $input;

    public function __construct(Application $application, $input = null)
    {
        $this->application = $application;
        $this->input = $input;
    }

    public function name(): string
    {
        return 'shell';
    }

    public function description(): string
    {
        return 'интерактивный режим: shell --machine=snacks, выход - exit';
    }

    public function run(Input $input, Output $output): int
    {
        $output->line('Команды вводятся без "bin/vending": status, insert 10, buy A2, refund. Выход - exit');

        // опции запуска действуют на каждую команду, пока их не переопределить в строке
        $defaults = [];

        foreach ($input->options() as $name => $value) {
            $defaults[] = $value === true ? '--' . $name : '--' . $name . '=' . $value;
        }

        while (($line = $this->read($output)) !== null) {
            $words = preg_split('/\s+/', trim($line), -1, PREG_SPLIT_NO_EMPTY);

            if (!$words) {
                continue;
            }

            if (in_array($words[0], ['exit', 'quit'], true)) {
                break;
            }

            if ($words[0] === $this->name()) {
                $output->error('интерактивный режим уже запущен');
                continue;
            }

            $this->application->run(new Input(array_merge($defaults, $words)));
            $output->line();
        }

        return 0;
    }

    private function read(Output $output): ?string
    {
        if ($this->input === null && function_exists('readline')) {
            $line = readline(self::PROMPT);

            if ($line === false) {
                return null;
            }

            readline_add_history($line);

            return $line;
        }

        $output->write(self::PROMPT);
        $line = fgets($this->input ?: STDIN);

        return $line === false ? null : $line;
    }
}
