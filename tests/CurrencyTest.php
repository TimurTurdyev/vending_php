<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Vending\Money\Currency;

final class CurrencyTest extends TestCase
{
    public function testParsesAmountsIntoMinorUnits(): void
    {
        $rub = new Currency('RUB', 'руб.');

        $this->assertSame(1300, $rub->parse('13'));
        $this->assertSame(1350, $rub->parse('13.5'));
        $this->assertSame(1305, $rub->parse('13,05'));
        $this->assertSame(50, $rub->parse('0.50'));
    }

    public function testRejectsAmountsFinerThanCurrency(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new Currency('RUB', 'руб.'))->parse('13.505');
    }

    public function testFormatsWithoutZeroKopecks(): void
    {
        $rub = new Currency('RUB', 'руб.');
        $eur = new Currency('EUR', '€');

        $this->assertSame('13 руб.', $rub->format(1300));
        $this->assertSame('13,50 руб.', $rub->format(1350));
        $this->assertSame('1 278 руб.', $rub->format(127800));
        $this->assertSame('0,10 €', $eur->format(10));
        $this->assertSame('13.05', $rub->plain(1305));
    }
}
