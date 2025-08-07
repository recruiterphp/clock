<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Recruiter\DateTime\UTCDateTime;
use Recruiter\UTCClock;

#[CoversClass(SettableUTCClock::class)]
#[UsesClass(UTCDateTime::class)]
class SettableUTCClockTest extends TestCase
{
    private UTCClock&MockObject $innerClock;
    private SettableUTCClock $clock;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->innerClock = $this->createMock(UTCClock::class);
        $this->clock = new SettableUTCClock($this->innerClock);
    }

    public function testCurrentShouldReturnStubbedTime(): void
    {
        $time = UTCDateTime::box('2015-02-01 10:00');
        $this->clock->setCurrent($time);

        $this->assertEquals(
            $time,
            $this->clock->current(),
        );
    }

    public function testCurrentShouldBeAskedToInnerClockIfNotSet(): void
    {
        $time = UTCDateTime::box('2015-02-01 10:00');

        $this->innerClock
            ->expects($this->any())
            ->method('current')
            ->willReturn($time)
        ;

        $this->assertEquals(
            $time,
            $this->clock->current(),
        );
    }

    public function testStubbedTimeCanBeReset(): void
    {
        $time = UTCDateTime::box('2015-02-01 10:00');

        $this->innerClock
            ->expects($this->any())
            ->method('current')
            ->willReturn($time)
        ;

        $this->clock->setCurrent(
            UTCDateTime::box('1985-05-21 08:40'),
        );

        $this->clock->reset();

        $this->assertEquals(
            $time,
            $this->clock->current(),
        );
    }

    public function testElapseAdvancesTimeAndReturnsNewTime(): void
    {
        $initialTime = UTCDateTime::box('2015-02-01 10:00:00');
        $this->clock->setCurrent($initialTime);

        $interval = new \DateInterval('PT1H'); // 1 hour
        $result = $this->clock->elapse($interval);

        $expectedTime = UTCDateTime::box('2015-02-01 11:00:00');
        $this->assertEquals($expectedTime, $result);
        $this->assertEquals($expectedTime, $this->clock->current());
    }
}
