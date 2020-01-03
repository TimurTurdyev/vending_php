<?php
declare(strict_types=1);

namespace Vending\Rules;

final class EveryNthFree implements PriceRule
{
    use Named;

    /** @var string */
    private $sku;

    /** @var int */
    private $every;

    public function __construct(string $id, string $label, string $sku, int $every)
    {
        $this->id = $id;
        $this->label = $label;
        $this->sku = $sku;
        $this->every = max(2, $every);
    }

    public static function fromArray(array $data): self
    {
        return new self($data['id'], $data['label'], $data['sku'], (int)$data['every']);
    }

    public function apply(int $price, SaleContext $context): int
    {
        if ($context->product()->sku() !== $this->sku) {
            return $price;
        }

        return ($context->sold() + 1) % $this->every === 0 ? 0 : $price;
    }
}
