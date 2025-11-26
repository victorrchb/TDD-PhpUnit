<?php

declare(strict_types=1);

namespace MyWeeklyAllowance;

use InvalidArgumentException;
use RuntimeException;

final class AllowanceAccount
{
    private int $balance = 0;

    /** @var array<int, array{type:string, amount:int, label?:string}> */
    private array $history = [];

    public function __construct(
        private readonly string $teenId,
        private readonly int $weeklyAllowance
    ) {
        if ($weeklyAllowance <= 0) {
            throw new InvalidArgumentException('Weekly allowance must be positive.');
        }
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function getWeeklyAllowance(): int
    {
        return $this->weeklyAllowance;
    }

    /**
     * @return array<int, array{type:string, amount:int, label?:string}>
     */
    public function getHistory(): array
    {
        return $this->history;
    }

    public function deposit(int $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Deposit amount must be positive.');
        }

        $this->balance += $amount;
        $this->history[] = [
            'type' => 'deposit',
            'amount' => $amount,
        ];
    }

    public function recordExpense(int $amount, string $label): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Expense amount must be positive.');
        }

        if ($amount > $this->balance) {
            throw new RuntimeException('Fonds insuffisants pour cette dépense.');
        }

        $this->balance -= $amount;
        $this->history[] = [
            'type' => 'expense',
            'amount' => $amount,
            'label' => $label,
        ];
    }

    public function applyWeeklyAllowance(): void
    {
        $this->balance += $this->weeklyAllowance;
        $this->history[] = [
            'type' => 'weekly_allowance',
            'amount' => $this->weeklyAllowance,
        ];
    }
}

