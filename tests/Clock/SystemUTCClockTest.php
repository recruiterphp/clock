<?php

declare(strict_types=1);

namespace Clock;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Recruiter\Clock\SystemUTCClock;
use Recruiter\DateTime\UTCDateTime;

#[CoversClass(SystemUTCClock::class)]
#[UsesClass(UTCDateTime::class)]
class SystemUTCClockTest extends TestCase
{
    public function testItReturnsCurrentUTCTime(): void
    {
        $clock = new SystemUTCClock();
        $before = UTCDateTime::now();
        $result = $clock->current();
        $after = UTCDateTime::now();

        $this->assertGreaterThanOrEqual($before, $result);
        $this->assertLessThanOrEqual($after, $result);
    }
}
