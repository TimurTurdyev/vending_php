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

    /** @var CashPolicy */
    private $policy;

    public function __construct(Currency $currency, Coins $coins, int $credit, ChangeMaker $changeMaker, CashPolicy $policy)
    {
        $this->currency = $currency;
        $this->coins = $coins;
        $this->credit = $credit;
        $this->changeMaker = $changeMaker;
        $this->policy = $policy;
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

    public function policy(): CashPolicy
    {
        return $this->policy;
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

        $change = $this->credit - $price;

        if ($change > 0 && $this->exactChangeOnly()) {
            throw VendingException::exactChangeOnly();
        }

        if ($this->policy->maxChange() !== null && $change > $this->policy->maxChange()) {
            throw VendingException::changeLimit($this->currency->format($this->policy->maxChange()));
        }

        return $this->payOut($change);
    }

    public function refund(): Coins
    {
        if ($this->credit === 0) {
            throw VendingException::nothingToRefund();
        }

        return $this->payOut($this->credit);
    }

    // мелочь почти кончилась: какой-то номинал, кроме самого крупного, ниже запаса
    public function exactChangeOnly(): bool
    {
        if ($this->policy->reserve() === 0) {
            return false;
        }

        foreach (array_slice($this->coins->denominations(), 1) as $value) {
            if ($this->coins->of($value) < $this->policy->reserve()) {
                return true;
            }
        }

        return false;
    }

    public function smallestCoin(): int
    {
        $values = $this->coins->denominations();

        return $values ? (int)end($values) : 1;
    }

    public function collect(): Coins
    {
        if ($this->credit > 0) {
            throw VendingException::creditInside();
        }

        $taken = [];

        foreach ($this->coins as $value => $count) {
            if ($count > $this->policy->keep()) {
                $taken[$value] = $count - $this->policy->keep();
            }
        }

        $taken = new Coins($taken);
        $this->coins = $this->coins->subtract($taken);

        return $taken;
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
