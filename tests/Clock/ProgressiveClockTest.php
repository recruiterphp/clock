<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ProgressiveClock::class)]
class ProgressiveClockTest extends ClockTestCase
{
    private \DateTime $start;
    private ProgressiveClock $clock;

    protected function setUp(): void
    {
        $this->start = new \DateTime('2025-01-02');
        $this->clock = new ProgressiveClock($this->start);
    }

    public function testIsMonotonic(): void
    {
        $this->assertGreaterThan($this->clock->now(), $this->clock->now());
    }

    public function testSleep(): void
    {
        $before = $this->clock->now(); // this advances the clock by 1 second after returning the current time
        $this->clock->sleep(1);
        $after = $this->clock->now();

        $this->assertEquals($before->add(new \DateInterval('PT2S')), $after);
    }

    public function testForwardInTimeAdvancesTheClock(): void
    {
        $before = $this->clock->now();
        $interval = new \DateInterval('PT2H'); // 2 hours
        $this->clock->forwardInTime($interval);
        $after = $this->clock->now();

        $expected = $before->add($interval)->add(new \DateInterval('PT1S')); // +1s because now() advances by default
        $this->assertDateTimeEquals($expected, $after);
    }
}
