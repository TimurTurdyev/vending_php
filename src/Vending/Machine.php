<?php
declare(strict_types=1);

namespace Vending;

use Vending\Change\ChangeMaker;

final class Machine
{
    /** @var Product[] номер => напиток */
    private $products = [];

    /** @var Coins монеты для сдачи вместе с внесенными */
    private $bank;

    /** @var int */
    private $credit;

    /** @var ChangeMaker */
    private $changeMaker;

    public function __construct(array $products, Coins $bank, int $credit, ChangeMaker $changeMaker)
    {
        foreach ($products as $product) {
            $this->products[$product->id()] = $product;
        }

        $this->bank = $bank;
        $this->credit = $credit;
        $this->changeMaker = $changeMaker;
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

    public function sell(int $productId): Purchase
    {
        if (!isset($this->products[$productId])) {
            throw VendingException::unknownProduct($productId);
        }

        $product = $this->products[$productId];

        if (!$product->inStock()) {
            throw VendingException::outOfStock($product);
        }

        if ($this->credit < $product->price()) {
            throw VendingException::insufficientFunds();
        }

        $change = $this->payOut($this->credit - $product->price());
        $this->products[$productId] = $product->take();

        return new Purchase($product, $change);
    }

    public function refund(): Purchase
    {
        if ($this->credit === 0) {
            throw VendingException::nothingToRefund();
        }

        return new Purchase(null, $this->payOut($this->credit));
    }

    private function payOut(int $amount): Coins
    {
        $change = $this->changeMaker->make($amount, $this->bank);

        if ($change === null) {
            throw VendingException::cannotMakeChange($amount);
        }

        $this->bank = $this->bank->subtract($change);
        $this->credit = 0;

        return $change;
    }
}
