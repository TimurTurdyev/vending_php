<?php
declare(strict_types=1);

namespace Vending\Rules;

use Vending\VendingException;

final class AgeCheck implements Restriction
{
    use Named;

    public function __construct(string $id, string $label)
    {
        $this->id = $id;
        $this->label = $label;
    }

    public static function fromArray(array $data): self
    {
        return new self($data['id'], $data['label']);
    }

    public function check(SaleContext $context): void
    {
        if ($context->product()->adult() && !$context->ageConfirmed()) {
            throw VendingException::ageNotConfirmed($context->product()->name());
        }
    }
}
