<?php
declare(strict_types=1);

use Vending\VendingException;

final class MachineTest extends VendingTestCase
{
    public function testChangeGoesToWalletAndSaleToJournal(): void
    {
        $machine = $this->machine();
        $customer = $this->customer();
        $this->insert($machine, $customer, [1000, 1000]);

        $purchase = $machine->buy($customer, 'A1', $this->time('12:00'));

        $this->assertSame('Чай', $purchase->product()->name());
        $this->assertSame([500 => 1, 200 => 1], $purchase->change()->toArray());
        $this->assertSame(18000 - 1300, $customer->wallet($machine->currency())->total());
        $this->assertSame(0, $machine->cash()->credit());
        $this->assertSame(4, $machine->slot('A1')->quantity());
        $this->assertCount(1, $machine->journal()->all());
    }

    public function testFailedPurchaseChangesNothing(): void
    {
        $machine = $this->machine();
        $customer = $this->customer();
        $this->insert($machine, $customer, [1000]);

        try {
            $machine->buy($customer, 'A2', $this->time('12:00'));
            $this->fail('purchase should be rejected');
        } catch (VendingException $e) {
            $this->assertSame('Недостаточно средств', $e->getMessage());
        }

        $this->assertSame(1000, $machine->cash()->credit());
        $this->assertSame(1, $machine->slot('A2')->quantity());
        $this->assertSame([], $machine->journal()->all());
    }

    public function testChangeLimitAndExactChangeMode(): void
    {
        $limited = $this->machine(['cash' => ['coins' => [100 => 10, 200 => 10, 500 => 10, 1000 => 10], 'max_change' => 500]]);
        $customer = $this->customer();
        $this->insert($limited, $customer, [1000, 1000]);

        try {
            $limited->buy($customer, 'A1', $this->time('12:00'));
            $this->fail('change over limit');
        } catch (VendingException $e) {
            $this->assertStringContainsString('не больше 5 руб.', $e->getMessage());
        }

        $exact = $this->machine(['cash' => ['coins' => [100 => 2, 200 => 10, 500 => 10, 1000 => 10], 'reserve' => 5]]);
        $this->assertTrue($exact->cash()->exactChangeOnly());

        $this->insert($exact, $customer, [1000, 200, 100]);
        $this->assertTrue($exact->buy($customer, 'A1', $this->time('12:00'))->change()->isEmpty());
    }
}
