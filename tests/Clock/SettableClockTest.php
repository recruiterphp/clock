<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Symfony\Component\Clock\DatePoint;
use Symfony\Component\Clock\MockClock;

#[CoversClass(SettableClock::class)]
#[UsesClass(ManualClock::class)]
class SettableClockTest extends ClockTestCase
{
    private MockClock $innerClock;
    private SettableClock $clock;
    private DatePoint $initialTime;

    protected function setUp(): void
    {
        $this->innerClock = new MockClock('2005-02-01 10:00');
        $this->initialTime = $this->innerClock->now();
        $this->clock = new SettableClock($this->innerClock);
    }

    public function testCurrentShouldReturnStubbedTime(): void
    {
        $time = new \DateTimeImmutable('2015-02-01 10:00 UTC');
        $this->clock->nowIs($time);

        $this->assertDateTimeEquals($time, $this->clock->now());
    }

    public function testCurrentShouldBeAskedToInnerClockIfNotSet(): void
    {
        $time = new \DateTimeImmutable('2005-02-01 10:00');

        $this->assertDateTimeEquals($time, $this->clock->now());
    }

    public function testStubbedTimeCanBeReset(): void
    {
        $this->clock->nowIs(new \DateTimeImmutable('1985-05-21 08:40'));

        $this->clock->reset();

        $this->assertDateTimeEquals($this->initialTime, $this->clock->now());
    }

    public function testElapseAdvancesTimeAndReturnsNewTime(): void
    {
        $initialTime = new \DateTimeImmutable('2015-02-01 10:00:00');
        $this->clock->nowIs($initialTime);

        $interval = new \DateInterval('PT1H'); // 1 hour
        $result = $this->clock->elapse($interval);

        $expectedTime = new \DateTimeImmutable('2015-02-01 11:00:00');
        $this->assertDateTimeEquals($expectedTime, $result);
        $this->assertDateTimeEquals($expectedTime, $this->clock->now());
    }

    public function testSleep(): void
    {
        $initialTime = new \DateTimeImmutable('2015-02-01 10:00:00');
        $this->clock->nowIs($initialTime);

        $this->clock->sleep(1);

        $expectedTime = new \DateTimeImmutable('2015-02-01 10:00:01');
        $this->assertEquals($expectedTime, $this->clock->now());
    }

    /**
     * @throws \DateMalformedStringException
     * @throws \DateInvalidTimeZoneException
     */
    public function testWithTimeZoneShouldChangeTimezoneInBothClocks(): void
    {
        $initialTime = new \DateTimeImmutable('2015-04-01 10:00:00', new \DateTimeZone('UTC'));
        $this->clock->nowIs($initialTime);
        $newClock = $this->clock->withTimeZone('Europe/Berlin');

        $expectedTime = new \DateTimeImmutable('2015-04-01 12:00:00', new \DateTimeZone('Europe/Berlin'));
        $this->assertDateTimeEquals(
            $expectedTime,
            $newClock->now(),
            'Clock should convert time to the new timezone correctly',
        );

        $newClock->reset();

        $this->assertDateTimeEquals(
            $this->initialTime->setTimezone(new \DateTimeZone('Europe/Berlin')),
            $now = $newClock->now(),
            'Resetting the new clock should not affect the original clock',
        );
    }
}
