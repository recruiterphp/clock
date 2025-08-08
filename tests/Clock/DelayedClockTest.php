<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Clock\MockClock;

#[CoversClass(DelayedClock::class)]
class DelayedClockTest extends ClockTestCase
{
    public function testGivesATimeAFewSecondsInThePast(): void
    {
        $original = new MockClock('2025-01-02T12:34:56.789012+00:00');
        $clock = new DelayedClock($original, 10);

        $this->assertDateTimeEquals(
            new \DateTimeImmutable('2025-01-02T12:34:46.789012+00:00'),
            $clock->now(),
        );
    }
}
