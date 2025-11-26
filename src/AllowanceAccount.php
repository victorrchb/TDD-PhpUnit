<?php

declare(strict_types=1);

namespace MyWeeklyAllowance;

use InvalidArgumentException;
use RuntimeException;

final class AllowanceAccount
{
    private int $balance = 0;

    /** @var array<int, array{type:string, amount:int, label?:string, occurred_at:string}> */
    private array $history = [];
    private Clock $clock;

    public function __construct(
        private readonly string $teenId,
        private readonly int $weeklyAllowance,
        ?Clock $clock = null
    ) {
        if ($weeklyAllowance <= 0) {
            throw new InvalidArgumentException('Weekly allowance must be positive.');
        }

        $this->clock = $clock ?? new SystemClock();
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
     * @return array<int, array{type:string, amount:int, label?:string, occurred_at:string}>
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
            'occurred_at' => $this->clock->now(),
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
            'occurred_at' => $this->clock->now(),
        ];
    }

    public function applyWeeklyAllowance(): void
    {
        $this->balance += $this->weeklyAllowance;
        $this->history[] = [
            'type' => 'weekly_allowance',
            'amount' => $this->weeklyAllowance,
            'occurred_at' => $this->clock->now(),
        ];
    }
}

