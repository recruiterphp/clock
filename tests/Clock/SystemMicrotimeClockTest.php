<?php

declare(strict_types=1);

namespace Clock;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Recruiter\Clock\SystemMicrotimeClock;

#[CoversClass(SystemMicrotimeClock::class)]
class SystemMicrotimeClockTest extends TestCase
{
    public function testItReturnsCurrentMicrotime(): void
    {
        $clock = new SystemMicrotimeClock();
        $before = microtime(true);
        $result = $clock->current();
        $after = microtime(true);

        $this->assertGreaterThanOrEqual($before, $result);
        $this->assertLessThanOrEqual($after, $result);
    }
}
