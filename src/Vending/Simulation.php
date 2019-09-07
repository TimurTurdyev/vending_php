<?php
declare(strict_types=1);

namespace Vending;

// покупатель с кошельком у аппарата
final class Simulation
{
    /** @var Machine */
    private $machine;

    /** @var Coins */
    private $wallet;

    public function __construct(Machine $machine, Coins $wallet)
    {
        $this->machine = $machine;
        $this->wallet = $wallet;
    }

    public function machine(): Machine
    {
        return $this->machine;
    }

    public function wallet(): Coins
    {
        return $this->wallet;
    }

    public function insert(int $coin): void
    {
        if ($this->wallet->of($coin) === 0) {
            throw VendingException::emptyWallet($coin);
        }

        $this->machine->accept($coin);
        $this->wallet = $this->wallet->subtract(Coins::one($coin));
    }
}
