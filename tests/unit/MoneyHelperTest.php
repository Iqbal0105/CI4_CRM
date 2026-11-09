<?php

declare(strict_types=1);

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use InvalidArgumentException;

final class MoneyHelperTest extends CIUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        helper('metafora');
    }

    public function testFormatRupiah(): void
    {
        $this->assertSame('Rp 150.000', format_rupiah('150000.00'));
        $this->assertSame('Rp 0', format_rupiah('0'));
        $this->assertSame('Rp 1.250.500', format_rupiah('1250500'));
    }

    public function testMoneyAdd(): void
    {
        $sum = money_add('15000.50', '25000.25');
        $this->assertSame('40000.75', $sum);
    }

    public function testMoneySub(): void
    {
        $diff = money_sub('50000.00', '12500.50');
        $this->assertSame('37499.50', $diff);
    }

    public function testMoneyMul(): void
    {
        $product = money_mul('12500.00', '3');
        $this->assertSame('37500.00', $product);

        $fraction = money_mul('100.555', '2', 2);
        $this->assertSame('201.11', $fraction);
    }

    public function testMoneyDiv(): void
    {
        $quotient = money_div('100.00', '4');
        $this->assertSame('25.00', $quotient);
    }

    public function testMoneyDivByZeroThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        money_div('100.00', '0');
    }

    public function testMoneyRound(): void
    {
        $this->assertSame('100.55', money_round('100.546', 2));
        $this->assertSame('100.54', money_round('100.544', 2));
        $this->assertSame('100.55', money_round('100.545', 2));
    }
}

