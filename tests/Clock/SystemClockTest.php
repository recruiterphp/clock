<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Recruiter\Clock\SystemClock;

#[CoversClass(SystemClock::class)]
class SystemClockTest extends TestCase
{
    public function testItReturnsCurrentTime(): void
    {
        $clock = new SystemClock();
        $before = new \DateTimeImmutable();
        $result = $clock->now();
        $after = new \DateTimeImmutable();

        $this->assertGreaterThanOrEqual($before, $result);
        $this->assertLessThanOrEqual($after, $result);
    }
}
