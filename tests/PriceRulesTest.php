<?php
declare(strict_types=1);

final class PriceRulesTest extends VendingTestCase
{
    public function testHappyHoursOnlyForCategoryInsideWindow(): void
    {
        $machine = $this->machine(['rules' => [
            ['id' => 'morning', 'type' => 'happy_hours', 'label' => 'Утро', 'from' => '08:00', 'to' => '10:00', 'percent' => 50, 'category' => 'hot'],
        ]]);
        $customer = $this->customer();

        $this->assertSame(650, $machine->quote($machine->slot('A1'), $customer, $this->time('08:00'))->price());
        $this->assertSame(1300, $machine->quote($machine->slot('A1'), $customer, $this->time('10:00'))->price());
        $this->assertSame(3500, $machine->quote($machine->slot('A2'), $customer, $this->time('09:00'))->price());
        $this->assertSame(['Утро'], $machine->quote($machine->slot('A1'), $customer, $this->time('09:59'))->labels());
    }

    public function testEveryNthIsFree(): void
    {
        $machine = $this->machine(['rules' => [
            ['id' => 'third', 'type' => 'every_nth_free', 'label' => 'Третий бесплатно', 'sku' => 'tea', 'every' => 3],
        ]]);
        $customer = $this->customer();
        $prices = [];

        for ($i = 0; $i < 4; $i++) {
            $this->insert($machine, $customer, [1000, 500]);
            $prices[] = $machine->buy($customer, 'A1', $this->time('12:00'))->quote()->price();
        }

        $this->assertSame([1300, 1300, 0, 1300], $prices);
    }

    public function testNightMarkupAcrossMidnight(): void
    {
        $machine = $this->machine(['rules' => [
            ['id' => 'night', 'type' => 'night_markup', 'label' => 'Ночь', 'from' => '22:00', 'to' => '06:00', 'percent' => 10],
        ]]);
        $customer = $this->customer();

        $this->assertSame(1430, $machine->quote($machine->slot('A1'), $customer, $this->time('23:30'))->price());
        $this->assertSame(1430, $machine->quote($machine->slot('A1'), $customer, $this->time('05:59'))->price());
        $this->assertSame(1300, $machine->quote($machine->slot('A1'), $customer, $this->time('06:00'))->price());
    }

    public function testRulesApplyInOrder(): void
    {
        $machine = $this->machine(['rules' => [
            ['id' => 'night', 'type' => 'night_markup', 'label' => 'Ночь', 'from' => '00:00', 'to' => '23:59', 'percent' => 100],
            ['id' => 'sale', 'type' => 'happy_hours', 'label' => 'Скидка', 'from' => '00:00', 'to' => '23:59', 'percent' => 50],
        ]]);
        $customer = $this->customer();
        $slot = $machine->slot('A1');

        $this->assertSame(1300, $machine->quote($slot, $customer, $this->time('12:00'))->price());
    }
}
