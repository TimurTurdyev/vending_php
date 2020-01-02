<?php
declare(strict_types=1);

namespace Vending\Rules;

trait Named
{
    /** @var string */
    private $id;

    /** @var string */
    private $label;

    public function id(): string
    {
        return $this->id;
    }

    public function label(): string
    {
        return $this->label;
    }
}
