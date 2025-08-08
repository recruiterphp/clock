<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Recruiter\DateTime\UTCDateTime;
use Recruiter\StopWatch\ClockStopWatch;

#[CoversClass(ManualClock::class)]
#[UsesClass(PsrMicrotimeClock::class)]
#[UsesClass(ClockStopWatch::class)]
#[UsesClass(PsrUTCClock::class)]
#[UsesClass(UTCDateTime::class)]
class ManualClockTest extends ClockTestCase
{
    public function testItReturnsInitialTime(): void
    {
        $initialTime = new \DateTime('2023-10-01 12:00:00');
        $clock = new ManualClock($initialTime);

        $this->assertDateTimeEquals(
            \DateTimeImmutable::createFromMutable($initialTime),
            $clock->now(),
            'Clock should return the initial time',
        );
    }

    public function testItReturnsSameTimeOnMultipleCalls(): void
    {
        $clock = new ManualClock(new \DateTimeImmutable('2023-10-01 12:00:00'));

        $this->assertDateTimeEquals(
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

        $this->assertDateTimeEquals(
            new \DateTimeImmutable('2023-10-01 12:00:00'),
            $clock->now(),
            'Clock should return the fixed time even if the mutable DateTime is modified',
        );
    }

    public function testFromIso8601(): void
    {
        $clock = ManualClock::fromIso8601('2023-10-01T12:00:00Z');

        $this->assertDateTimeEquals(
            new \DateTimeImmutable('2023-10-01 12:00:00', new \DateTimeZone('Z')),
            $clock->now(),
            'Clock should return the fixed time from ISO 8601 string',
        );
    }

    public function testItAdvancesTimeBySeconds(): void
    {
        $initialTime = new \DateTime('2023-10-01 12:00:00');
        $clock = new ManualClock($initialTime);

        $clock->advance(3600); // 1 hour

        $this->assertDateTimeEquals(
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

        $this->assertDateTimeEquals(
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

        $this->assertDateTimeEquals(
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

        $this->assertDateTimeEquals(
            $newTime,
            $clock->now(),
            'Clock should jump to the specified time',
        );
    }

    public function testConversionToMicrotime(): void
    {
        $fixedTime = new \DateTimeImmutable('2023-10-01 12:00:00.123456');
        $clock = new ManualClock($fixedTime)->asMicrotime();

        $this->assertSame(
            1696161600.123456,
            $clock->now(),
            'Clock should convert fixed time to microtime correctly',
        );
    }

    public function testConversionToUTC(): void
    {
        $fixedTime = new \DateTimeImmutable('2023-10-01 12:00:00', new \DateTimeZone('Europe/Berlin'));
        $clock = new ManualClock($fixedTime)->asUTC();

        $this->assertEquals(
            UTCDateTime::fromString('2023-10-01 10:00:00'),
            $clock->now(),
            'Clock should convert fixed time to UTC correctly',
        );
    }

    public function testStopWatch(): void
    {
        $clock = new ManualClock(new \DateTimeImmutable('2023-10-01 12:00:00'));
        $stopwatch = $clock->stopWatch();

        $stopwatch->start();
        $clock->advance(2);
        $elapsed = $stopwatch->elapsedSeconds();

        $this->assertEquals(2, $elapsed, 'Stopwatch should measure elapsed time correctly');
    }

    public function testSleep(): void
    {
        $clock = new ManualClock(new \DateTimeImmutable('2023-10-01 12:00:00'));
        $clock->sleep(1.5);
        $expectedTime = new \DateTimeImmutable('2023-10-01 12:00:01.500000');

        $this->assertDateTimeEquals(
            $expectedTime,
            $clock->now(),
            'Clock should advance time by the specified sleep duration',
        );
    }

    public function testWithTimeZone(): void
    {
        $clock = new ManualClock(new \DateTimeImmutable('2023-10-01 12:00:00', new \DateTimeZone('Europe/Berlin')));
        $newClock = $clock->withTimeZone('America/New_York');

        $this->assertDateTimeEquals(
            new \DateTimeImmutable('2023-10-01 06:00:00', new \DateTimeZone('America/New_York')),
            $newClock->now(),
            'Clock should return time in the new timezone',
        );
    }
}
