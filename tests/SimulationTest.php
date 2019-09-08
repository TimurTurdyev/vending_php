<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Vending\Change\OptimalChange;
use Vending\Coins;
use Vending\Machine;
use Vending\Product;
use Vending\Simulation;
use Vending\VendingException;

final class SimulationTest extends TestCase
{
    public function testChangeGoesBackToWallet(): void
    {
        $simulation = $this->simulation(new Coins([1 => 5, 2 => 5, 5 => 5, 10 => 5]));
        $simulation->insert(10);
        $simulation->insert(10);

        $purchase = $simulation->buy(1);

        $this->assertSame('Чай', $purchase->product()->name());
        $this->assertSame([5 => 1, 2 => 1], array_filter($purchase->change()->toArray()));
        $this->assertSame(0, $simulation->machine()->credit());
        $this->assertSame(0, $simulation->machine()->products()[1]->amount());
        $this->assertSame(20 - 13, $simulation->wallet()->total());
        $this->assertSame(90 + 13, $simulation->machine()->bank()->total());
    }

    public function testNotEnoughMoneyKeepsCreditAndStock(): void
    {
        $simulation = $this->simulation(new Coins([1 => 5, 2 => 5, 5 => 5, 10 => 5]));
        $simulation->insert(10);

        try {
            $simulation->buy(1);
            $this->fail('purchase should be rejected');
        } catch (VendingException $e) {
            $this->assertSame('Недостаточно средств', $e->getMessage());
        }

        $this->assertSame(10, $simulation->machine()->credit());
        $this->assertSame(1, $simulation->machine()->products()[1]->amount());
    }

    public function testNoChangeInMachineCancelsPurchase(): void
    {
        $simulation = $this->simulation(new Coins([1 => 0, 2 => 0, 5 => 0, 10 => 0]));
        $simulation->insert(10);
        $simulation->insert(10);

        $this->expectException(VendingException::class);

        try {
            $simulation->buy(1);
        } finally {
            $this->assertSame(20, $simulation->machine()->credit());
            $this->assertSame(1, $simulation->machine()->products()[1]->amount());
        }
    }

    public function testRefundReturnsWholeCredit(): void
    {
        $simulation = $this->simulation(new Coins([1 => 0, 2 => 0, 5 => 0, 10 => 0]));
        $simulation->insert(10);
        $simulation->insert(10);

        $this->assertSame([10 => 2], array_filter($simulation->refund()->change()->toArray()));
        $this->assertSame(20, $simulation->wallet()->total());
    }

    private function simulation(Coins $bank): Simulation
    {
        $machine = new Machine([new Product(1, 'Чай', 13, 1)], $bank, 0, new OptimalChange());

        return new Simulation($machine, new Coins([1 => 0, 2 => 0, 5 => 0, 10 => 2]));
    }
}
