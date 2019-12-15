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

    public function __construct(string $id, string $name, string $location = '')
    {
        $this->id = $id;
        $this->name = $name;
        $this->location = $location;
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
}
