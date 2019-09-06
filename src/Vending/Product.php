<?php
declare(strict_types=1);

namespace Vending;

use InvalidArgumentException;

final class Product
{
    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var int */
    private $price;

    /** @var int */
    private $amount;

    public function __construct(int $id, string $name, int $price, int $amount)
    {
        if ($price <= 0 || $amount < 0) {
            throw new InvalidArgumentException(sprintf('Неверная цена или остаток у напитка "%s"', $name));
        }

        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->amount = $amount;
    }

    public static function fromArray(array $data): self
    {
        return new self((int)$data['id'], (string)$data['name'], (int)$data['price'], (int)$data['amount']);
    }

    public function toArray(): array
    {
        return ['id' => $this->id, 'name' => $this->name, 'price' => $this->price, 'amount' => $this->amount];
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): int
    {
        return $this->price;
    }

    public function amount(): int
    {
        return $this->amount;
    }
}
