<?php
declare(strict_types=1);

namespace Vending\Rules;

use DateTimeImmutable;
use InvalidArgumentException;

// интервал внутри суток, может переходить через полночь: 22:00-06:00
final class TimeRange
{
    /** @var int минуты от начала суток */
    private $from;

    /** @var int */
    private $to;

    public function __construct(string $from, string $to)
    {
        $this->from = self::minutes($from);
        $this->to = self::minutes($to);
    }

    public function contains(DateTimeImmutable $time): bool
    {
        $minute = (int)$time->format('G') * 60 + (int)$time->format('i');

        if ($this->from <= $this->to) {
            return $minute >= $this->from && $minute < $this->to;
        }

        return $minute >= $this->from || $minute < $this->to;
    }

    public function __toString(): string
    {
        return sprintf('%02d:%02d-%02d:%02d', intdiv($this->from, 60), $this->from % 60, intdiv($this->to, 60), $this->to % 60);
    }

    private static function minutes(string $time): int
    {
        if (!preg_match('/^([01]?\d|2[0-3]):([0-5]\d)$/', $time, $matches)) {
            throw new InvalidArgumentException(sprintf('Неверное время "%s", нужно ЧЧ:ММ', $time));
        }

        return (int)$matches[1] * 60 + (int)$matches[2];
    }
}
