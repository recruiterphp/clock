<?php

declare(strict_types=1);

namespace Clock;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Recruiter\Clock\AcceleratedClock;

#[CoversClass(AcceleratedClock::class)]
class AcceleratedClockTest extends TestCase
{
    private \DateTimeImmutable $start;
    private AcceleratedClock $clock;

    protected function setUp(): void
    {
        $this->start = new \DateTimeImmutable('2025-01-02');
        $this->clock = new AcceleratedClock($this->start);
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function testItCanBeForwarded(): void
    {
        $this->clock->advance(new \DateInterval('PT1H'));
        $expected = $this->start->modify('+1 hour');

        $this->assertEquals(
            $expected,
            $this->clock->now(),
            'Clock should be advanced by 1 hour',
        );
    }
}
