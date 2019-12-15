<?php
declare(strict_types=1);

namespace Vending;

use Vending\Money\Coins;
use Vending\Money\Currency;

final class Customer
{
    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var Coins[] код валюты => монеты */
    private $wallets = [];

    public function __construct(string $id, string $name, array $wallets = [])
    {
        $this->id = $id;
        $this->name = $name;

        foreach ($wallets as $code => $coins) {
            $this->wallets[$code] = $coins instanceof Coins ? $coins : new Coins($coins);
        }
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    /** @return Coins[] */
    public function wallets(): array
    {
        return $this->wallets;
    }

    public function wallet(Currency $currency): Coins
    {
        return $this->wallets[$currency->code()] ?? new Coins();
    }

    public function spend(Currency $currency, int $coin): void
    {
        if ($this->wallet($currency)->of($coin) === 0) {
            throw VendingException::emptyWallet($currency->format($coin));
        }

        $this->wallets[$currency->code()] = $this->wallet($currency)->subtract(Coins::one($coin));
    }

    public function receive(Currency $currency, Coins $coins): void
    {
        $this->wallets[$currency->code()] = $this->wallet($currency)->add($coins);
    }
}
