<?php
declare(strict_types=1);

namespace Vending\Catalog;

use InvalidArgumentException;

final class Product
{
    /** @var string */
    private $sku;

    /** @var string */
    private $name;

    /** @var string */
    private $category;

    /** @var int */
    private $price;

    public function __construct(string $sku, string $name, string $category, int $price)
    {
        if ($price <= 0) {
            throw new InvalidArgumentException(sprintf('У товара "%s" должна быть цена', $name));
        }

        $this->sku = $sku;
        $this->name = $name;
        $this->category = $category;
        $this->price = $price;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (string)$data['sku'],
            (string)$data['name'],
            (string)($data['category'] ?? 'other'),
            (int)$data['price']
        );
    }

    public function toArray(): array
    {
        return [
            'sku' => $this->sku,
            'name' => $this->name,
            'category' => $this->category,
            'price' => $this->price,
        ];
    }

    public function sku(): string
    {
        return $this->sku;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function category(): string
    {
        return $this->category;
    }

    public function price(): int
    {
        return $this->price;
    }
}
