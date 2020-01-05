<?php
declare(strict_types=1);

namespace Vending\Rules;

interface Restriction extends Rule
{
    // бросает VendingException, если продавать нельзя
    public function check(SaleContext $context): void;
}
