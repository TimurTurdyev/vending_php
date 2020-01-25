<?php
declare(strict_types=1);

use Vending\VendingException;

final class RestrictionsTest extends VendingTestCase
{
    public function testSellsOnlyInWorkingHours(): void
    {
        $machine = $this->machine(['rules' => [
            ['id' => 'hours', 'type' => 'working_hours', 'label' => 'С 7 до 23', 'from' => '07:00', 'to' => '23:00'],
        ]]);
        $customer = $this->customer();
        $this->insert($machine, $customer, [1000, 500], '23:10');

        $this->expectExceptionMessage('Аппарат работает 07:00-23:00');
        $machine->buy($customer, 'A1', $this->time('23:10'));
    }

    public function testLimitCountsOnlyTodayPurchasesOfThisCustomer(): void
    {
        $machine = $this->machine(['rules' => [
            ['id' => 'limit', 'type' => 'purchase_limit', 'label' => 'Две в день', 'per_day' => 2],
        ]]);
        $ivan = $this->customer('ivan');
        $maria = $this->customer('maria');

        foreach ([$ivan, $ivan, $maria] as $customer) {
            $this->insert($machine, $customer, [1000, 500]);
            $machine->buy($customer, 'A1', $this->time('12:00'));
        }

        $this->insert($machine, $ivan, [1000, 500]);

        try {
            $machine->buy($ivan, 'A1', $this->time('18:00'));
            $this->fail('third purchase today');
        } catch (VendingException $e) {
            $this->assertSame('Не больше 2 покупок в день на покупателя', $e->getMessage());
        }

        $this->assertSame('Чай', $machine->buy($ivan, 'A1', $this->time('18:00')->modify('+1 day'))->product()->name());
    }

    public function testAdultProductNeedsConfirmation(): void
    {
        $machine = $this->machine(['rules' => [
            ['id' => 'adult', 'type' => 'age_check', 'label' => '18+'],
        ]]);
        $customer = $this->customer();
        $this->insert($machine, $customer, [1000, 1000, 1000, 1000, 1000, 1000, 1000, 1000, 1000]);

        try {
            $machine->buy($customer, 'A3', $this->time('12:00'));
            $this->fail('no age confirmation');
        } catch (VendingException $e) {
            $this->assertStringContainsString('только с 18 лет', $e->getMessage());
        }

        $this->assertSame('Энергетик', $machine->buy($customer, 'A3', $this->time('12:00'), true)->product()->name());
    }
}
