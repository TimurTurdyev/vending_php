<?php
declare(strict_types=1);

namespace App\Console;

use DateTimeImmutable;
use Framework\Clock\Clock;
use Framework\Console\Command;
use Framework\Console\Input;
use Framework\Console\Output;
use InvalidArgumentException;
use Vending\Customer;
use Vending\Machine;
use Vending\Money\Coins;
use Vending\Money\Currency;
use Vending\VendingException;
use Vending\World;
use Vending\WorldRepository;

// команда работает с миром в одной транзакции: при ошибке ничего не сохраняется
abstract class WorldCommand implements Command
{
    /** @var WorldRepository */
    private $world;

    /** @var Clock */
    private $clock;

    /** @var Defaults */
    private $defaults;

    public function __construct(WorldRepository $world, Clock $clock, Defaults $defaults)
    {
        $this->world = $world;
        $this->clock = $clock;
        $this->defaults = $defaults;
    }

    final public function run(Input $input, Output $output): int
    {
        try {
            $this->world->ensureCustomer($this->customerId($input));
            $this->world->transaction(function (World $world) use ($input, $output) {
                $this->handle($world, $input, $output, $this->clock->now());
            });
        } catch (VendingException | InvalidArgumentException $e) {
            $output->error($e->getMessage());

            return 1;
        }

        return 0;
    }

    abstract protected function handle(World $world, Input $input, Output $output, DateTimeImmutable $now): void;

    protected function machine(World $world, Input $input): Machine
    {
        return $world->machine((string)$input->option('machine', $this->defaults->machine()));
    }

    protected function customer(World $world, Input $input): Customer
    {
        return $world->customer($this->customerId($input));
    }

    protected function argument(Input $input, int $index, string $usage): string
    {
        $value = $input->argument($index);

        if ($value === null || $value === '') {
            throw new InvalidArgumentException('использование: ' . $usage);
        }

        return $value;
    }

    protected function describe(Coins $coins, Currency $currency): string
    {
        $parts = [];

        foreach ($coins as $coin => $count) {
            if ($count > 0) {
                $parts[] = sprintf('%s x %d', $currency->format($coin), $count);
            }
        }

        return $parts ? implode(', ', $parts) . ' = ' . $currency->format($coins->total()) : 'нет';
    }

    private function customerId(Input $input): string
    {
        return (string)$input->option('as', $this->defaults->customer());
    }
}
