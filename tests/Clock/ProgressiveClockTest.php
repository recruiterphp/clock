<?php

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
        $this->assertGreaterThan($this->clock->current(), $this->clock->current());
    }
}