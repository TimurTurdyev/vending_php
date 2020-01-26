<?php
declare(strict_types=1);

use Vending\VendingException;
use Vending\World;

final class OccupancyTest extends VendingTestCase
{
    public function testMachineIsBusyWhileSomeoneHasCredit(): void
    {
        $machine = $this->machine();
        $ivan = $this->customer('ivan');
        $maria = $this->customer('maria');
        $this->insert($machine, $ivan, [1000]);

        $this->assertTrue($machine->busyFor($maria, $this->time('12:01')));

        try {
            $machine->insert($maria, 500, $this->time('12:01'));
            $this->fail('machine is busy');
        } catch (VendingException $e) {
            $this->assertSame(10, $maria->wallet($machine->currency())->of(500));
        }

        $machine->refund($ivan, $this->time('12:01'));
        $this->assertFalse($machine->busyFor($maria, $this->time('12:01')));
    }

    public function testAbandonedCreditReturnsToItsOwner(): void
    {
        $machine = $this->machine(['occupancy_timeout' => 60]);
        $ivan = $this->customer('ivan');
        $maria = $this->customer('maria');
        $world = new World([$machine], [$ivan, $maria]);
        $this->insert($machine, $ivan, [1000, 500], '12:00');

        $world->releaseAbandoned($this->time('12:00:59'));
        $this->assertSame(1500, $machine->cash()->credit());

        $world->releaseAbandoned($this->time('12:01:00'));
        $this->assertSame(0, $machine->cash()->credit());
        $this->assertSame(18000, $ivan->wallet($machine->currency())->total());
        $this->assertFalse($machine->busyFor($maria, $this->time('12:01')));
    }
}
