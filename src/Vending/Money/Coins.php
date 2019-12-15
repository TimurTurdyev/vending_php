<?php
declare(strict_types=1);

namespace Vending\Money;

use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;

// неизменяемый набор монет: номинал => количество, от крупных к мелким
final class Coins implements IteratorAggregate, Countable
{
    /** @var int[] */
    private $counts = [];

    public function __construct(array $counts = [])
    {
        foreach ($counts as $value => $count) {
            if ((int)$value <= 0 || (int)$count < 0) {
                throw new InvalidArgumentException(sprintf('Неверная монета: %s x %s', $value, $count));
            }

            $this->counts[(int)$value] = (int)$count;
        }

        krsort($this->counts);
    }

    public static function one(int $value): self
    {
        return new self([$value => 1]);
    }

    public function of(int $value): int
    {
        return $this->counts[$value] ?? 0;
    }

    public function accepts(int $value): bool
    {
        return isset($this->counts[$value]);
    }

    public function denominations(): array
    {
        return array_keys($this->counts);
    }

    public function total(): int
    {
        $total = 0;

        foreach ($this->counts as $value => $count) {
            $total += $value * $count;
        }

        return $total;
    }

    public function count(): int
    {
        return array_sum($this->counts);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function add(Coins $other): self
    {
        $counts = $this->counts;

        foreach ($other->counts as $value => $count) {
            $counts[$value] = ($counts[$value] ?? 0) + $count;
        }

        return new self($counts);
    }

    public function subtract(Coins $other): self
    {
        $counts = $this->counts;

        foreach ($other->counts as $value => $count) {
            if ($this->of($value) < $count) {
                throw new InvalidArgumentException(sprintf('Не хватает монет по %d руб.', $value));
            }

            $counts[$value] -= $count;
        }

        return new self($counts);
    }

    public function toArray(): array
    {
        return $this->counts;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->counts);
    }
}
