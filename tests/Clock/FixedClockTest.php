<?php

declare(strict_types=1);

namespace Clock;

use Recruiter\DateTime\UTCDateTime;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Recruiter\Clock\FixedClock;
use Recruiter\Clock\PsrMicrotimeClock;
use Recruiter\Clock\PsrUTCClock;

#[CoversClass(FixedClock::class)]
#[UsesClass(PsrMicrotimeClock::class)]
#[UsesClass(PsrUTCClock::class)]
#[UsesClass(UTCDateTime::class)]
class FixedClockTest extends TestCase
{
    public function testItReturnsFixedTime(): void
    {
        $fixedTime = new \DateTimeImmutable('2023-10-01 12:00:00');
        $clock = new FixedClock($fixedTime);

        $this->assertEquals(
            $fixedTime,
            $clock->now(),
            'Clock should return the fixed time',
        );
    }

    public function testItReturnsSameTimeOnMultipleCalls(): void
    {
        $clock = new FixedClock(new \DateTimeImmutable('2023-10-01 12:00:00'));

        $this->assertEquals(
            $clock->now(),
            $clock->now(),
            'Clock should return the same fixed time on subsequent calls',
        );
    }

    public function testItShouldAcceptMutableDateTime(): void
    {
        $mutableTime = new \DateTime('2023-10-01 12:00:00');
        $clock = new FixedClock($mutableTime);
        $mutableTime->modify('+1 hour');

        $this->assertEquals(
            new \DateTimeImmutable('2023-10-01 12:00:00'),
            $clock->now(),
            'Clock should return the fixed time even if the mutable DateTime is modified',
        );
    }

    public function testFromIso8601(): void
    {
        $clock = FixedClock::fromIso8601('2023-10-01T12:00:00Z');

        $this->assertEquals(
            new \DateTimeImmutable('2023-10-01 12:00:00', new \DateTimeZone('UTC')),
            $clock->now(),
            'Clock should return the fixed time from ISO 8601 string',
        );
    }

    public function testConversionToMicrotime(): void
    {
        $fixedTime = new \DateTimeImmutable('2023-10-01 12:00:00.123456');
        $clock = new FixedClock($fixedTime)->asMicrotime();

        $this->assertEquals(
            1696161600.123456,
            $clock->current(),
            'Clock should convert fixed time to microtime correctly',
        );
    }

    public function testConversionToUTC(): void
    {
        $fixedTime = new \DateTimeImmutable('2023-10-01 12:00:00', new \DateTimeZone('Europe/Berlin'));
        $clock = new FixedClock($fixedTime)->asUTC();

        $this->assertEquals(
            UTCDateTime::fromString('2023-10-01 10:00:00'),
            $clock->current(),
            'Clock should convert fixed time to UTC correctly',
        );
    }
}
