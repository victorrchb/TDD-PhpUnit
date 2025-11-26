<?php

declare(strict_types=1);

namespace MyWeeklyAllowance\Tests;

use InvalidArgumentException;
use MyWeeklyAllowance\AllowanceAccount;
use MyWeeklyAllowance\Clock;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class AllowanceAccountTest extends TestCase
{
    public function testNewAccountStartsWithZeroBalanceAndWeeklyAllowance(): void
    {
        $account = new AllowanceAccount('teen-1', 1000, new TestClock());

        $this->assertSame(0, $account->getBalance());
        $this->assertSame(1000, $account->getWeeklyAllowance());
    }

    public function testDepositIncreasesBalance(): void
    {
        $account = new AllowanceAccount('teen-1', 1000, new TestClock());

        $account->deposit(2500);

        $this->assertSame(2500, $account->getBalance());
    }

    public function testDepositMustBePositive(): void
    {
        $account = new AllowanceAccount('teen-1', 1000, new TestClock());

        $this->expectException(InvalidArgumentException::class);
        $account->deposit(0);
    }

    public function testExpensesDecreaseBalanceWhenFundsSufficient(): void
    {
        $account = new AllowanceAccount('teen-1', 1000, new TestClock());
        $account->deposit(2000);

        $account->recordExpense(1500, 'livre');

        $this->assertSame(500, $account->getBalance());
        $history = $account->getHistory();
        $this->assertCount(2, $history);
        $this->assertSame('expense', $history[1]['type']);
        $this->assertSame(1500, $history[1]['amount']);
        $this->assertSame('livre', $history[1]['label']);
    }

    public function testExpenseWithInsufficientFundsDoesNotChangeBalance(): void
    {
        $account = new AllowanceAccount('teen-1', 1000, new TestClock());
        $account->deposit(500);

        $this->expectException(RuntimeException::class);
        $account->recordExpense(600, 'jeu video');

        $this->assertSame(500, $account->getBalance());
    }

    public function testWeeklyAllowanceAddsFundsAndIsTracked(): void
    {
        $account = new AllowanceAccount('teen-1', 1500, new TestClock());

        $account->applyWeeklyAllowance();

        $this->assertSame(1500, $account->getBalance());
        $history = $account->getHistory();
        $this->assertSame('weekly_allowance', $history[0]['type']);
        $this->assertSame(1500, $history[0]['amount']);
    }

    public function testHistoryEntriesAreTimestamped(): void
    {
        $clock = new TestClock();
        $account = new AllowanceAccount('teen-1', 1000, $clock);

        $account->applyWeeklyAllowance();

        $history = $account->getHistory();
        $this->assertArrayHasKey('occurred_at', $history[0]);
        $this->assertSame($clock->getFixedTime(), $history[0]['occurred_at']);
    }
}

final class TestClock implements Clock
{
    public function __construct(private readonly string $fixed = '2025-01-01T00:00:00+00:00')
    {
    }

    public function now(): string
    {
        return $this->fixed;
    }

    public function getFixedTime(): string
    {
        return $this->fixed;
    }
}

