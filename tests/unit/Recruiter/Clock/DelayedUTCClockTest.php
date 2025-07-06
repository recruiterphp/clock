<?php
namespace Recruiter\Clock;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Recruiter\DateTime\UTCDateTime;
use Recruiter\UTCClock;

#[CoversClass(DelayedUTCClock::class)]
class DelayedUTCClockTest extends TestCase
{
    public function testGivesATimeAFewSecondsInThePast()
    {
        $original = $this->createMock(UTCClock::class);
        $clock = new DelayedUTCClock($original, 10);

        $original->expects($this->once())
                 ->method('current')
                 ->will($this->returnValue(UTCDateTime::fromTimestamp(10000018)));

        $this->assertEquals(
            UTCDateTime::fromTimestamp(10000008),
            $clock->current()
        );
    }
}
