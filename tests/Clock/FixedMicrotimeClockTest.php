<?php

declare(strict_types=1);

namespace Clock;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Recruiter\Clock\FixedMicrotimeClock;

#[CoversClass(FixedMicrotimeClock::class)]
class FixedMicrotimeClockTest extends TestCase
{
    public function testItReturnsFixedMicrotime(): void
    {
        $fixedTime = 1234567890.123456;
        $clock = new FixedMicrotimeClock($fixedTime);

        $this->assertEquals($fixedTime, $clock->current());
    }

    public function testNowIsChangesTheTime(): void
    {
        $clock = new FixedMicrotimeClock(1000.0);
        $newTime = 2000.123456;

        $clock->nowIs($newTime);

        $this->assertEquals($newTime, $clock->current());
    }
}
