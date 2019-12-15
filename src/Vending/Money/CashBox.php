<?php
declare(strict_types=1);

namespace Vending\Money;

use Vending\VendingException;

final class CashBox
{
    /** @var Currency */
    private $currency;

    /** @var Coins все монеты в кассе, внесенные тоже */
    private $coins;

    /** @var int */
    private $credit;

    /** @var ChangeMaker */
    private $changeMaker;

    public function __construct(Currency $currency, Coins $coins, int $credit, ChangeMaker $changeMaker)
    {
        $this->currency = $currency;
        $this->coins = $coins;
        $this->credit = $credit;
        $this->changeMaker = $changeMaker;
    }

    public function currency(): Currency
    {
        return $this->currency;
    }

    public function coins(): Coins
    {
        return $this->coins;
    }

    public function credit(): int
    {
        return $this->credit;
    }

    public function accepts(int $coin): bool
    {
        return $this->coins->accepts($coin);
    }

    public function insert(int $coin): void
    {
        if (!$this->accepts($coin)) {
            throw VendingException::unknownCoin($this->currency->format($coin));
        }

        $this->coins = $this->coins->add(Coins::one($coin));
        $this->credit += $coin;
    }

    public function pay(int $price): Coins
    {
        if ($this->credit < $price) {
            throw VendingException::insufficientFunds();
        }

        return $this->payOut($this->credit - $price);
    }

    public function refund(): Coins
    {
        if ($this->credit === 0) {
            throw VendingException::nothingToRefund();
        }

        return $this->payOut($this->credit);
    }

    private function payOut(int $amount): Coins
    {
        $change = $this->changeMaker->make($amount, $this->coins);

        if ($change === null) {
            throw VendingException::cannotMakeChange($this->currency->format($amount));
        }

        $this->coins = $this->coins->subtract($change);
        $this->credit = 0;

        return $change;
    }
}
