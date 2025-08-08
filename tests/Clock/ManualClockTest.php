<?php

declare(strict_types=1);

namespace Clock;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Recruiter\Clock\ManualClock;
use Recruiter\Clock\PsrMicrotimeClock;
use Recruiter\Clock\PsrUTCClock;
use Recruiter\DateTime\UTCDateTime;

#[CoversClass(ManualClock::class)]
#[UsesClass(PsrMicrotimeClock::class)]
#[UsesClass(PsrUTCClock::class)]
#[UsesClass(UTCDateTime::class)]
class ManualClockTest extends TestCase
{
    public function testItReturnsInitialTime(): void
    {
        $initialTime = new \DateTime('2023-10-01 12:00:00');
        $clock = new ManualClock($initialTime);

        $this->assertEquals(
            \DateTimeImmutable::createFromMutable($initialTime),
            $clock->now(),
            'Clock should return the initial time',
        );
    }

    public function testItReturnsSameTimeOnMultipleCalls(): void
    {
        $clock = new ManualClock(new \DateTimeImmutable('2023-10-01 12:00:00'));

        $this->assertEquals(
            $clock->now(),
            $clock->now(),
            'Clock should return the same fixed time on subsequent calls',
        );
    }

    public function testItShouldAcceptMutableDateTime(): void
    {
        $mutableTime = new \DateTime('2023-10-01 12:00:00');
        $clock = new ManualClock($mutableTime);
        $mutableTime->modify('+1 hour');

        $this->assertEquals(
            new \DateTimeImmutable('2023-10-01 12:00:00'),
            $clock->now(),
            'Clock should return the fixed time even if the mutable DateTime is modified',
        );
    }

    public function testFromIso8601(): void
    {
        $clock = ManualClock::fromIso8601('2023-10-01T12:00:00Z');

        $this->assertEquals(
            new \DateTimeImmutable('2023-10-01 12:00:00', new \DateTimeZone('UTC')),
            $clock->now(),
            'Clock should return the fixed time from ISO 8601 string',
        );
    }

    public function testItAdvancesTimeBySeconds(): void
    {
        $initialTime = new \DateTime('2023-10-01 12:00:00');
        $clock = new ManualClock($initialTime);

        $clock->advance(3600); // 1 hour

        $this->assertEquals(
            new \DateTimeImmutable('2023-10-01 13:00:00'),
            $clock->now(),
            'Clock should advance by the specified seconds',
        );
    }

    public function testItAdvancesTimeByDateInterval(): void
    {
        $initialTime = new \DateTime('2023-10-01 12:00:00');
        $clock = new ManualClock($initialTime);

        $clock->advance(new \DateInterval('PT1H')); // 1 hour

        $this->assertEquals(
            new \DateTimeImmutable('2023-10-01 13:00:00'),
            $clock->now(),
            'Clock should advance by the specified interval',
        );
    }

    public function testItCanAdvanceMultipleTimes(): void
    {
        $initialTime = new \DateTime('2023-10-01 12:00:00');
        $clock = new ManualClock($initialTime);

        $clock->advance(1800); // 30 minutes
        $clock->advance(1800); // another 30 minutes

        $this->assertEquals(
            new \DateTimeImmutable('2023-10-01 13:00:00'),
            $clock->now(),
            'Clock should advance correctly with multiple calls',
        );
    }

    public function testNowIsChangesTime(): void
    {
        $clock = new ManualClock(new \DateTimeImmutable('2023-10-01 12:00:00'));
        $newTime = new \DateTimeImmutable('2025-01-02 15:30:45');

        $clock->nowIs($newTime);

        $this->assertEquals(
            $newTime,
            $clock->now(),
            'Clock should jump to the specified time',
        );
    }

    public function testConversionToMicrotime(): void
    {
        $fixedTime = new \DateTimeImmutable('2023-10-01 12:00:00.123456');
        $clock = new ManualClock($fixedTime)->asMicrotime();

        $this->assertEquals(
            1696161600.123456,
            $clock->current(),
            'Clock should convert fixed time to microtime correctly',
        );
    }

    public function testConversionToUTC(): void
    {
        $fixedTime = new \DateTimeImmutable('2023-10-01 12:00:00', new \DateTimeZone('Europe/Berlin'));
        $clock = new ManualClock($fixedTime)->asUTC();

        $this->assertEquals(
            UTCDateTime::fromString('2023-10-01 10:00:00'),
            $clock->current(),
            'Clock should convert fixed time to UTC correctly',
        );
    }
}
