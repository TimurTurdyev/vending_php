<?php
declare(strict_types=1);

namespace Vending\Rules;

interface Rule
{
    public function id(): string;

    public function label(): string;
}
