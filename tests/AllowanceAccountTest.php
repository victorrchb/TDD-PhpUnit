<?php

declare(strict_types=1);

namespace MyWeeklyAllowance\Tests;

use MyWeeklyAllowance\AllowanceAccount;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use InvalidArgumentException;

final class AllowanceAccountTest extends TestCase
{
    public function testNewAccountStartsWithZeroBalanceAndWeeklyAllowance(): void
    {
        $account = new AllowanceAccount('teen-1', 1000);

        $this->assertSame(0, $account->getBalance());
        $this->assertSame(1000, $account->getWeeklyAllowance());
    }

    public function testDepositIncreasesBalance(): void
    {
        $account = new AllowanceAccount('teen-1', 1000);

        $account->deposit(2500);

        $this->assertSame(2500, $account->getBalance());
    }

    public function testDepositMustBePositive(): void
    {
        $account = new AllowanceAccount('teen-1', 1000);

        $this->expectException(InvalidArgumentException::class);
        $account->deposit(0);
    }

    public function testExpensesDecreaseBalanceWhenFundsSufficient(): void
    {
        $account = new AllowanceAccount('teen-1', 1000);
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
        $account = new AllowanceAccount('teen-1', 1000);
        $account->deposit(500);

        $this->expectException(RuntimeException::class);
        $account->recordExpense(600, 'jeu video');

        $this->assertSame(500, $account->getBalance());
    }

    public function testWeeklyAllowanceAddsFundsAndIsTracked(): void
    {
        $account = new AllowanceAccount('teen-1', 1500);

        $account->applyWeeklyAllowance();

        $this->assertSame(1500, $account->getBalance());
        $history = $account->getHistory();
        $this->assertSame('weekly_allowance', $history[0]['type']);
        $this->assertSame(1500, $history[0]['amount']);
    }
}

