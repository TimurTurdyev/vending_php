<?php
declare(strict_types=1);

namespace Vending\Money;

use InvalidArgumentException;

// все суммы внутри хранятся целыми в копейках или центах, валюта умеет их читать и показывать
final class Currency
{
    /** @var string */
    private $code;

    /** @var string */
    private $symbol;

    /** @var int */
    private $digits;

    public function __construct(string $code, string $symbol, int $digits = 2)
    {
        $this->code = $code;
        $this->symbol = $symbol;
        $this->digits = $digits;
    }

    public static function fromArray(array $data): self
    {
        return new self((string)$data['code'], (string)$data['symbol'], (int)($data['digits'] ?? 2));
    }

    public function toArray(): array
    {
        return ['code' => $this->code, 'symbol' => $this->symbol, 'digits' => $this->digits];
    }

    public function code(): string
    {
        return $this->code;
    }

    // "13", "13.5", "13,50" -> 1350
    public function parse(string $amount): int
    {
        if (!preg_match('/^(\d+)(?:[.,](\d+))?$/', trim($amount), $matches) || strlen($matches[2] ?? '') > $this->digits) {
            throw new InvalidArgumentException(sprintf('Неверная сумма "%s"', $amount));
        }

        return (int)$matches[1] * 10 ** $this->digits + (int)str_pad($matches[2] ?? '', $this->digits, '0');
    }

    // для полей ввода: 1350 -> "13.50", 1300 -> "13"
    public function plain(int $amount): string
    {
        $factor = 10 ** $this->digits;
        $fraction = $amount % $factor;

        return intdiv($amount, $factor) . ($fraction > 0 ? '.' . str_pad((string)$fraction, $this->digits, '0', STR_PAD_LEFT) : '');
    }

    // 1350 -> "13,50 руб.", 1300 -> "13 руб."
    public function format(int $amount): string
    {
        $factor = 10 ** $this->digits;
        $fraction = $amount % $factor;
        $number = number_format(intdiv($amount, $factor), 0, ',', ' ');

        if ($fraction > 0) {
            $number .= ',' . str_pad((string)$fraction, $this->digits, '0', STR_PAD_LEFT);
        }

        return $number . ' ' . $this->symbol;
    }
}
