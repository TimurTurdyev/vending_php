<?php
declare(strict_types=1);

namespace Vending;

// владелец аппаратов, входит в панель по паролю
final class Operator
{
    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $passwordHash;

    public function __construct(string $id, string $name, string $passwordHash)
    {
        $this->id = $id;
        $this->name = $name;
        $this->passwordHash = $passwordHash;
    }

    public static function fromArray(array $data): self
    {
        return new self($data['id'], $data['name'], $data['password_hash']);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function verify(string $password): bool
    {
        return password_verify($password, $this->passwordHash);
    }
}
