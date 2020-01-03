<?php
declare(strict_types=1);

namespace Vending\Rules;

final class NightMarkup implements PriceRule
{
    use Named;

    /** @var TimeRange */
    private $hours;

    /** @var int */
    private $percent;

    public function __construct(string $id, string $label, TimeRange $hours, int $percent)
    {
        $this->id = $id;
        $this->label = $label;
        $this->hours = $hours;
        $this->percent = $percent;
    }

    public static function fromArray(array $data): self
    {
        return new self($data['id'], $data['label'], new TimeRange($data['from'], $data['to']), (int)$data['percent']);
    }

    public function apply(int $price, SaleContext $context): int
    {
        if (!$this->hours->contains($context->time())) {
            return $price;
        }

        return intdiv($price * (100 + $this->percent), 100);
    }
}
