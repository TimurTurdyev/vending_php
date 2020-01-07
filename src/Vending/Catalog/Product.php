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

    /** @var bool */
    private $adult;

    public function __construct(string $sku, string $name, string $category, int $price, bool $adult = false)
    {
        if ($price <= 0) {
            throw new InvalidArgumentException(sprintf('У товара "%s" должна быть цена', $name));
        }

        $this->sku = $sku;
        $this->name = $name;
        $this->category = $category;
        $this->price = $price;
        $this->adult = $adult;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (string)$data['sku'],
            (string)$data['name'],
            (string)($data['category'] ?? 'other'),
            (int)$data['price'],
            (bool)($data['adult'] ?? false)
        );
    }

    public function toArray(): array
    {
        return [
            'sku' => $this->sku,
            'name' => $this->name,
            'category' => $this->category,
            'price' => $this->price,
            'adult' => $this->adult,
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

    public function adult(): bool
    {
        return $this->adult;
    }
}
