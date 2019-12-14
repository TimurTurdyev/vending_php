<?php
declare(strict_types=1);

namespace Framework\Clock;

use DateTimeImmutable;

interface Clock
{
    public function now(): DateTimeImmutable;
}
