<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Psr\Clock\ClockInterface;
use Recruiter\MicrotimeClock;

final readonly class PsrMicrotimeClock implements MicrotimeClock
{
    public function __construct(private ClockInterface $wrapped)
    {
    }

    public function now(): float
    {
        return floatval($this->wrapped->now()->format('U.u'));
    }
}
