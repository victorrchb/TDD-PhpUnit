<?php

declare(strict_types=1);

namespace MyWeeklyAllowance;

use DateTimeImmutable;

final class SystemClock implements Clock
{
    public function now(): string
    {
        return (new DateTimeImmutable())->format(DATE_ATOM);
    }
}

