<?php
declare(strict_types=1);

namespace Vending;

use DomainException;

final class VendingException extends DomainException
{
    public static function insufficientFunds(): self
    {
        return new self('Недостаточно средств');
    }

    public static function outOfStock(Product $product): self
    {
        return new self(sprintf('%s закончился', $product->name()));
    }

    public static function unknownProduct(int $id): self
    {
        return new self(sprintf('Напитка с номером %d нет', $id));
    }

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
