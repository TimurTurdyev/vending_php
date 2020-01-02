<?php
declare(strict_types=1);

namespace Vending;

use Vending\Catalog\Product;
use Vending\Money\Coins;
use Vending\Rules\Quote;

// что получил покупатель: товар по цене (при возврате денег их нет) и сдачу
final class Purchase
{
    /** @var Product|null */
    private $product;

    /** @var Quote|null */
    private $quote;

    /** @var Coins */
    private $change;

    public function __construct(?Product $product, ?Quote $quote, Coins $change)
    {
        $this->product = $product;
        $this->quote = $quote;
        $this->change = $change;
    }

    public function product(): ?Product
    {
        return $this->product;
    }

    public function quote(): ?Quote
    {
        return $this->quote;
    }

    public function change(): Coins
    {
        return $this->change;
    }
}
