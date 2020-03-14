<?php
declare(strict_types=1);

namespace Vending\Catalog;

use InvalidArgumentException;
use Vending\VendingException;

// ячейка витрины: код на кнопке, вместимость и сколько в ней сейчас товара
final class Slot
{
    /** @var string */
    private $code;

    /** @var int */
    private $capacity;

    /** @var string|null */
    private $sku;

    /** @var int */
    private $quantity;

    public function __construct(string $code, int $capacity, ?string $sku = null, int $quantity = 0)
    {
        if ($capacity <= 0 || $quantity < 0 || $quantity > $capacity || ($sku === null && $quantity > 0)) {
            throw new InvalidArgumentException(sprintf('Неверная загрузка ячейки %s', $code));
        }

        $this->code = $code;
        $this->capacity = $capacity;
        $this->sku = $sku;
        $this->quantity = $quantity;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (string)$data['code'],
            (int)$data['capacity'],
            isset($data['sku']) ? (string)$data['sku'] : null,
            (int)($data['quantity'] ?? 0)
        );
    }

    public function toArray(): array
    {
        return ['code' => $this->code, 'capacity' => $this->capacity, 'sku' => $this->sku, 'quantity' => $this->quantity];
    }

    public function code(): string
    {
        return $this->code;
    }

    public function capacity(): int
    {
        return $this->capacity;
    }

    public function sku(): ?string
    {
        return $this->sku;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function inStock(): bool
    {
        return $this->quantity > 0;
    }

    public function take(): void
    {
        if (!$this->inStock()) {
            throw VendingException::emptySlot($this->code);
        }

        $this->quantity--;
    }

    // null - заполнить до конца
    public function restock(?int $quantity = null): int
    {
        if ($this->sku === null) {
            throw VendingException::emptySlot($this->code);
        }

        $added = min($quantity ?? $this->capacity, $this->capacity - $this->quantity);
        $this->quantity += max(0, $added);

        return max(0, $added);
    }

    // другой товар: старый остаток оператор забирает
    public function load(string $sku, int $quantity): void
    {
        if ($quantity < 0 || $quantity > $this->capacity) {
            throw VendingException::overCapacity($this->code, $this->capacity);
        }

        $this->sku = $sku;
        $this->quantity = $quantity;
    }
}
