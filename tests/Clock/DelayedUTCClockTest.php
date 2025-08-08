<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Recruiter\DateTime\UTCDateTime;
use Recruiter\UTCClock;

#[CoversClass(DelayedUTCClock::class)]
#[UsesClass(UTCDateTime::class)]
class DelayedUTCClockTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGivesATimeAFewSecondsInThePast(): void
    {
        $original = $this->createMock(UTCClock::class);
        $clock = new DelayedUTCClock($original, 10);

        $original->expects($this->once())
                 ->method('now')
                 ->willReturn(UTCDateTime::fromTimestamp(10000018))
        ;

        $this->assertEquals(
            UTCDateTime::fromTimestamp(10000008),
            $clock->now(),
        );
    }
}
