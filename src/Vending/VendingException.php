<?php
declare(strict_types=1);

namespace Vending;

use DomainException;

// сообщения сразу для покупателя: их показывают и веб, и консоль
final class VendingException extends DomainException
{
    public static function insufficientFunds(): self
    {
        return new self('Недостаточно средств');
    }

    public static function exactChangeOnly(): self
    {
        return new self('В аппарате мало мелочи, он продает только без сдачи');
    }

    public static function changeLimit(string $limit): self
    {
        return new self(sprintf('Аппарат выдает сдачу не больше %s', $limit));
    }

    public static function cannotMakeChange(string $amount): self
    {
        return new self(sprintf('В аппарате не хватает монет, чтобы выдать %s', $amount));
    }

    public static function nothingToRefund(): self
    {
        return new self('Внесенная сумма равна нулю');
    }

    public static function unknownCoin(string $coin): self
    {
        return new self(sprintf('Аппарат не принимает монеты %s', $coin));
    }

    public static function emptyWallet(string $coin): self
    {
        return new self(sprintf('В кошельке нет монет %s', $coin));
    }

    public static function creditInside(): self
    {
        return new self('В аппарате внесены деньги покупателя, сначала их нужно вернуть');
    }

    public static function unknownSlot(string $code): self
    {
        return new self(sprintf('Ячейки %s нет', $code));
    }

    public static function emptySlot(string $code): self
    {
        return new self(sprintf('Ячейка %s пуста', $code));
    }

    public static function unknownProduct(string $sku): self
    {
        return new self(sprintf('Товара "%s" нет в каталоге аппарата', $sku));
    }

    public static function unknownMachine(string $id): self
    {
        return new self(sprintf('Аппарата "%s" нет', $id));
    }

    public static function unknownCustomer(string $id): self
    {
        return new self(sprintf('Покупателя "%s" нет', $id));
    }
}
