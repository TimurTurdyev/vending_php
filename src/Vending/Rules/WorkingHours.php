<?php
declare(strict_types=1);

namespace Vending\Rules;

use Vending\VendingException;

final class WorkingHours implements Restriction
{
    use Named;

    /** @var TimeRange */
    private $hours;

    public function __construct(string $id, string $label, TimeRange $hours)
    {
        $this->id = $id;
        $this->label = $label;
        $this->hours = $hours;
    }

    public static function fromArray(array $data): self
    {
        return new self($data['id'], $data['label'], new TimeRange($data['from'], $data['to']));
    }

    public function check(SaleContext $context): void
    {
        if (!$this->hours->contains($context->time())) {
            throw VendingException::closed((string)$this->hours);
        }
    }
}
