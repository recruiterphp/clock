<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Symfony\Component\Clock\ClockInterface;

class DelayedClock extends AbstractClock
{
    use SymfonySupport;

    private readonly \DateInterval $delay;

    public function __construct(ClockInterface $wrapped, int $delayInSeconds)
    {
        $this->wrapped = $wrapped;
        $this->delay = new \DateInterval("PT{$delayInSeconds}S");
    }

    public function now(): \DateTimeImmutable
    {
        return $this->wrapped->now()->sub($this->delay);
    }
}
