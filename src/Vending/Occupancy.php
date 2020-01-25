<?php
declare(strict_types=1);

namespace Vending;

use DateTimeImmutable;

// кто сейчас стоит у аппарата и с какого момента
final class Occupancy
{
    /** @var string */
    private $customer;

    /** @var int */
    private $since;

    public function __construct(string $customer, int $since)
    {
        $this->customer = $customer;
        $this->since = $since;
    }

    public function customer(): string
    {
        return $this->customer;
    }

    public function since(): int
    {
        return $this->since;
    }

    public function secondsLeft(DateTimeImmutable $now, int $timeout): int
    {
        return max(0, $this->since + $timeout - $now->getTimestamp());
    }
}
