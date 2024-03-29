<?php
namespace Recruiter\Clock;

use Recruiter\DateTime\UTCDateTime;

class DelayedUTCClockTest extends \PHPUnit_Framework_TestCase
{
    public function testGivesATimeAFewSecondsInThePast()
    {
        $original = $this->getMock('Recruiter\UTCClock');
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
