<?php
declare(strict_types=1);

namespace Vending\Rules;

// цена товара для покупателя прямо сейчас и правила, которые ее изменили
final class Quote
{
    /** @var int */
    private $base;

    /** @var int */
    private $price;

    /** @var string[] */
    private $labels;

    public function __construct(int $base, int $price, array $labels = [])
    {
        $this->base = $base;
        $this->price = $price;
        $this->labels = $labels;
    }

    public function base(): int
    {
        return $this->base;
    }

    public function price(): int
    {
        return $this->price;
    }

    public function labels(): array
    {
        return $this->labels;
    }

    public function changed(): bool
    {
        return $this->price !== $this->base;
    }

    // скидка в процентах может дать сумму, которую нельзя набрать монетами
    public function roundedTo(int $step): self
    {
        return new self(self::roundUp($this->base, $step), self::roundUp($this->price, $step), $this->labels);
    }

    private static function roundUp(int $amount, int $step): int
    {
        return (int)(ceil($amount / $step) * $step);
    }
}
