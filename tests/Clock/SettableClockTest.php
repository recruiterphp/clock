<?php

declare(strict_types=1);

namespace Clock;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Recruiter\Clock\SettableClock;

#[CoversClass(SettableClock::class)]
class SettableClockTest extends TestCase
{
    public function testItReturnsInitialTime(): void
    {
        $initialTime = new \DateTime('2023-10-01 12:00:00');
        $clock = new SettableClock($initialTime);

        $this->assertEquals(
            \DateTimeImmutable::createFromMutable($initialTime),
            $clock->now(),
            'Clock should return the initial time',
        );
    }

    public function testItAdvancesTimeBySeconds(): void
    {
        $initialTime = new \DateTime('2023-10-01 12:00:00');
        $clock = new SettableClock($initialTime);

        $clock->advance(3600); // 1 hour

        $this->assertEquals(
            new \DateTimeImmutable('2023-10-01 13:00:00'),
            $clock->now(),
            'Clock should advance by the specified seconds',
        );
    }

    public function testItCanAdvanceMultipleTimes(): void
    {
        $initialTime = new \DateTime('2023-10-01 12:00:00');
        $clock = new SettableClock($initialTime);

        $clock->advance(1800); // 30 minutes
        $clock->advance(1800); // another 30 minutes

        $this->assertEquals(
            new \DateTimeImmutable('2023-10-01 13:00:00'),
            $clock->now(),
            'Clock should advance correctly with multiple calls',
        );
    }
}
