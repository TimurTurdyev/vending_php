<?php
declare(strict_types=1);

namespace Framework\Clock;

use DateTimeImmutable;
use DateTimeZone;

final class SystemClock implements Clock
{
    /** @var DateTimeZone */
    private $timezone;

    public function __construct(string $timezone)
    {
        $this->timezone = new DateTimeZone($timezone);
    }

    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', $this->timezone);
    }
}
