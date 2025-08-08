<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\ClockInterface;

#[CoversClass(SettableClock::class)]
#[UsesClass(ManualClock::class)]
class SettableClockTest extends TestCase
{
    private ClockInterface&MockObject $innerClock;
    private SettableClock $clock;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->innerClock = $this->createMock(ClockInterface::class);
        $this->clock = new SettableClock($this->innerClock);
    }

    public function testCurrentShouldReturnStubbedTime(): void
    {
        $time = new \DateTimeImmutable('2015-02-01 10:00 UTC');
        $this->clock->nowIs($time);

        $this->assertEquals(
            $time,
            $this->clock->now(),
        );
    }

    public function testCurrentShouldBeAskedToInnerClockIfNotSet(): void
    {
        $time = new \DateTimeImmutable('2015-02-01 10:00');

        $this->innerClock
            ->expects($this->any())
            ->method('now')
            ->willReturn($time)
        ;

        $this->assertEquals(
            $time,
            $this->clock->now(),
        );
    }

    public function testStubbedTimeCanBeReset(): void
    {
        $time = new \DateTimeImmutable('2015-02-01 10:00');

        $this->innerClock
            ->expects($this->any())
            ->method('now')
            ->willReturn($time)
        ;

        $this->clock->nowIs(new \DateTimeImmutable('1985-05-21 08:40'));

        $this->clock->reset();

        $this->assertEquals($time, $this->clock->now());
    }

    public function testElapseAdvancesTimeAndReturnsNewTime(): void
    {
        $initialTime = new \DateTimeImmutable('2015-02-01 10:00:00');
        $this->clock->nowIs($initialTime);

        $interval = new \DateInterval('PT1H'); // 1 hour
        $result = $this->clock->elapse($interval);

        $expectedTime = new \DateTimeImmutable('2015-02-01 11:00:00');
        $this->assertEquals($expectedTime, $result);
        $this->assertEquals($expectedTime, $this->clock->now());
    }

    public function testSleep(): void
    {
        $initialTime = new \DateTimeImmutable('2015-02-01 10:00:00');
        $this->clock->nowIs($initialTime);

        $this->clock->sleep(1);

        $expectedTime = new \DateTimeImmutable('2015-02-01 10:00:01');
        $this->assertEquals($expectedTime, $this->clock->now());
    }
}
