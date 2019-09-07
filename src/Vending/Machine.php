<?php
declare(strict_types=1);

namespace Vending;

final class Machine
{
    /** @var Product[] номер => напиток */
    private $products = [];

    /** @var Coins монеты для сдачи вместе с внесенными */
    private $bank;

    /** @var int */
    private $credit;

    public function __construct(array $products, Coins $bank, int $credit)
    {
        foreach ($products as $product) {
            $this->products[$product->id()] = $product;
        }

        $this->bank = $bank;
        $this->credit = $credit;
    }

    /** @return Product[] */
    public function products(): array
    {
        return $this->products;
    }

    public function bank(): Coins
    {
        return $this->bank;
    }

    public function credit(): int
    {
        return $this->credit;
    }

    public function accept(int $coin): void
    {
        if (!$this->bank->accepts($coin)) {
            throw VendingException::unknownCoin($coin);
        }

        $this->bank = $this->bank->add(Coins::one($coin));
        $this->credit += $coin;
    }
}
