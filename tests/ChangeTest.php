<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Vending\Money\Coins;
use Vending\Money\GreedyChange;
use Vending\Money\OptimalChange;

final class ChangeTest extends TestCase
{
    public function testFewestCoinsFromFullStock(): void
    {
        $bank = new Coins([1 => 100, 2 => 100, 5 => 100, 10 => 100]);

        $this->assertSame([10 => 2, 2 => 1, 1 => 1], (new OptimalChange())->make(23, $bank)->toArray());
        $this->assertTrue((new OptimalChange())->make(0, $bank)->isEmpty());
    }

    public function testFindsChangeWhereGreedyFails(): void
    {
        $bank = new Coins([1 => 0, 2 => 3, 5 => 1]);

        $this->assertNull((new GreedyChange())->make(6, $bank));
        $this->assertSame([2 => 3], (new OptimalChange())->make(6, $bank)->toArray());
    }

    public function testNoChangeWhenAmountCannotBeCollected(): void
    {
        $this->assertNull((new OptimalChange())->make(3, new Coins([2 => 5, 5 => 1])));
    }

    public function testMatchesBruteForceOnLimitedStock(): void
    {
        mt_srand(2019);

        for ($i = 0; $i < 500; $i++) {
            $bank = new Coins([1 => mt_rand(0, 3), 2 => mt_rand(0, 3), 5 => mt_rand(0, 3), 10 => mt_rand(0, 3)]);
            $amount = mt_rand(0, 60);
            $change = (new OptimalChange())->make($amount, $bank);
            $expected = $this->fewestCoins($amount, $bank);

            if ($expected === null) {
                $this->assertNull($change, "amount $amount");
                continue;
            }

            $this->assertNotNull($change, "amount $amount");
            $this->assertSame($amount, $change->total());
            $this->assertSame($expected, count($change));

            foreach ($change as $value => $count) {
                $this->assertLessThanOrEqual($bank->of($value), $count);
            }
        }
    }

    private function fewestCoins(int $amount, Coins $bank): ?int
    {
        $best = null;

        for ($a = 0; $a <= $bank->of(10); $a++) {
            for ($b = 0; $b <= $bank->of(5); $b++) {
                for ($c = 0; $c <= $bank->of(2); $c++) {
                    $d = $amount - 10 * $a - 5 * $b - 2 * $c;

                    if ($d >= 0 && $d <= $bank->of(1) && ($best === null || $a + $b + $c + $d < $best)) {
                        $best = $a + $b + $c + $d;
                    }
                }
            }
        }

        return $best;
    }
}
