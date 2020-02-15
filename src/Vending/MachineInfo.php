<?php
declare(strict_types=1);

namespace Vending;

final class MachineInfo
{
    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $location;

    /** @var string|null */
    private $operator;

    /** @var int секунды, через которые брошенная сумма возвращается покупателю */
    private $timeout;

    public function __construct(string $id, string $name, string $location = '', ?string $operator = null, int $timeout = 120)
    {
        $this->id = $id;
        $this->name = $name;
        $this->location = $location;
        $this->operator = $operator;
        $this->timeout = $timeout;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function location(): string
    {
        return $this->location;
    }

    public function operator(): ?string
    {
        return $this->operator;
    }

    public function timeout(): int
    {
        return $this->timeout;
    }
}
