<?php

declare(strict_types=1);

namespace MyWeeklyAllowance;

interface Clock
{
    public function now(): string;
}

