<?php

declare(strict_types=1);

namespace Clock;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Recruiter\Clock\ProgressiveClock;

#[CoversClass(ProgressiveClock::class)]
class ProgressiveClockTest extends TestCase
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

    public function testForwardInTimeAdvancesTheClock(): void
    {
        $before = $this->clock->now();
        $interval = new \DateInterval('PT2H'); // 2 hours
        $this->clock->forwardInTime($interval);
        $after = $this->clock->now();

        $expected = $before->add($interval)->add(new \DateInterval('PT1S')); // +1s because now() advances by default
        $this->assertEquals($expected, $after);
    }
}
