<?php
declare(strict_types=1);

namespace Vending\Rules;

interface PriceRule extends Rule
{
    public function apply(int $price, SaleContext $context): int;
}
