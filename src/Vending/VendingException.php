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

    public static function nothingToRefund(): self
    {
        return new self('Внесенная сумма равна нулю');
    }

    public static function cannotMakeChange(int $amount): self
    {
        return new self(sprintf('В аппарате не хватает монет, чтобы выдать сдачу %d руб.', $amount));
    }
}
