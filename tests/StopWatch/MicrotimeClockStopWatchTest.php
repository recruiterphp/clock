<?php

declare(strict_types=1);

namespace Recruiter\StopWatch;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Recruiter\Clock\FixedMicrotimeClock;

#[CoversClass(MicrotimeClockStopWatch::class)]
class MicrotimeClockStopWatchTest extends TestCase
{
    private FixedMicrotimeClock $clock;
    private MicrotimeClockStopWatch $stopWatch;

    protected function setUp(): void
    {
        $this->clock = new FixedMicrotimeClock(45.123456);
        $this->stopWatch = new MicrotimeClockStopWatch($this->clock);
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
        $this->clock->nowIs(98.987653);

        $this->assertEqualsWithDelta(53.864197, $this->stopWatch->elapsedSeconds(), 0.000001);
        $this->assertEqualsWithDelta(53864.197, $this->stopWatch->elapsedMilliseconds(), 0.001);
        $this->assertEqualsWithDelta(53864197, $this->stopWatch->elapsedMicroseconds(), 1);
    }
}
