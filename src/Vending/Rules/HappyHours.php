<?php
declare(strict_types=1);

namespace Vending\Rules;

final class HappyHours implements PriceRule
{
    use Named;

    /** @var TimeRange */
    private $hours;

    /** @var int */
    private $percent;

    /** @var string|null */
    private $category;

    public function __construct(string $id, string $label, TimeRange $hours, int $percent, ?string $category = null)
    {
        $this->id = $id;
        $this->label = $label;
        $this->hours = $hours;
        $this->percent = $percent;
        $this->category = $category;
    }

    public static function fromArray(array $data): self
    {
        return new self($data['id'], $data['label'], new TimeRange($data['from'], $data['to']), (int)$data['percent'], $data['category'] ?? null);
    }

    public function apply(int $price, SaleContext $context): int
    {
        if (!$this->hours->contains($context->time())) {
            return $price;
        }

        if ($this->category !== null && $this->category !== $context->product()->category()) {
            return $price;
        }

        return intdiv($price * (100 - $this->percent), 100);
    }
}
