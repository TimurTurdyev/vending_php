<?php
declare(strict_types=1);

namespace Vending;

// итог операции: выданный напиток (при возврате денег его нет) и сдача
final class Purchase
{
    /** @var Product|null */
    private $product;

    /** @var Coins */
    private $change;

    public function __construct(?Product $product, Coins $change)
    {
        $this->product = $product;
        $this->change = $change;
    }

    public function product(): ?Product
    {
        return $this->product;
    }

    public function change(): Coins
    {
        return $this->change;
    }
}
