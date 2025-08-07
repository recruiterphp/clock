<?php

declare(strict_types=1);

namespace Recruiter\StopWatch;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Recruiter\Clock\FixedClock;

#[CoversClass(ClockStopWatch::class)]
#[UsesClass(FixedClock::class)]
#[UsesClass(StopWatchNotStartedException::class)]
class ClockStopWatchTest extends TestCase
{
    private FixedClock $clock;
    private ClockStopWatch $stopWatch;

    /**
     * @throws \DateMalformedStringException
     */
    protected function setUp(): void
    {
        $this->clock = FixedClock::fromIso8601('2015-02-03 00:12:43');
        $this->stopWatch = new ClockStopWatch($this->clock);
    }

    public function testElapsedSecondsWithoutStarting(): void
    {
        $this->expectException(StopWatchNotStartedException::class);
        $this->stopWatch->elapsedSeconds();
    }

    public function testElapsedMillisecondsWithoutStarting(): void
    {
        $this->expectException(StopWatchNotStartedException::class);
        $this->stopWatch->elapsedMilliseconds();
    }

    public function testElapsedMicrosecondsWithoutStarting(): void
    {
        $this->expectException(StopWatchNotStartedException::class);
        $this->stopWatch->elapsedMicroseconds();
    }

    public function testElapsedAfterStopping(): void
    {
        $this->stopWatch->start();
        $this->clock->nowIs(new \DateTime('2015-02-03 00:12:49'));

        $this->assertEqualsWithDelta(6.0, $this->stopWatch->elapsedSeconds(), 0.1);
        $this->assertEqualsWithDelta(6000.0, $this->stopWatch->elapsedMilliseconds(), 0.1);
        $this->assertEqualsWithDelta(6000000.0, $this->stopWatch->elapsedMicroseconds(), 0.1);
    }
}
