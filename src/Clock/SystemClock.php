<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use Symfony\Component\Clock\NativeClock;

class SystemClock extends AbstractClock
{
    use SymfonySupport;

    /**
     * @throws \DateInvalidTimeZoneException
     */
    public function __construct(\DateTimeZone|string|null $timezone = null)
    {
        $this->wrapped = new NativeClock($timezone);
    }

    public function now(): \DateTimeImmutable
    {
        return $this->wrapped->now();
    }
}
