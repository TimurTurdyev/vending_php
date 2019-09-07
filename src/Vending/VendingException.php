<?php
declare(strict_types=1);

namespace Vending;

use DomainException;

final class VendingException extends DomainException
{
    public static function unknownCoin(int $value): self
    {
        return new self(sprintf('Аппарат не принимает монеты по %d руб.', $value));
    }

    public static function emptyWallet(int $value): self
    {
        return new self(sprintf('В кошельке нет монет по %d руб.', $value));
    }
}
