<?php
declare(strict_types=1);

namespace Vending\Money;

// как аппарат обращается с деньгами, задается в настройках аппарата
final class CashPolicy
{
    /** @var int|null */
    private $maxChange;

    /** @var int */
    private $reserve;

    /**
     * @param int|null $maxChange самая большая сдача, null - без ограничения
     * @param int $reserve если мелких монет меньше, аппарат продает только без сдачи
     */
    public function __construct(?int $maxChange = null, int $reserve = 0)
    {
        $this->maxChange = $maxChange;
        $this->reserve = $reserve;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['max_change']) ? (int)$data['max_change'] : null,
            (int)($data['reserve'] ?? 0)
        );
    }

    public function toArray(): array
    {
        return ['max_change' => $this->maxChange, 'reserve' => $this->reserve];
    }

    public function maxChange(): ?int
    {
        return $this->maxChange;
    }

    public function reserve(): int
    {
        return $this->reserve;
    }
}
