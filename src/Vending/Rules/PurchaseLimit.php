<?php
declare(strict_types=1);

namespace Vending\Rules;

use Vending\VendingException;

final class PurchaseLimit implements Restriction
{
    use Named;

    /** @var int */
    private $perDay;

    public function __construct(string $id, string $label, int $perDay)
    {
        $this->id = $id;
        $this->label = $label;
        $this->perDay = $perDay;
    }

    public static function fromArray(array $data): self
    {
        return new self($data['id'], $data['label'], (int)$data['per_day']);
    }

    public function check(SaleContext $context): void
    {
        if ($context->customerToday() >= $this->perDay) {
            throw VendingException::limitReached($this->perDay);
        }
    }
}
