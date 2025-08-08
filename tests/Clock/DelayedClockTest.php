<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\ClockInterface;

#[CoversClass(DelayedClock::class)]
class DelayedClockTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGivesATimeAFewSecondsInThePast(): void
    {
        $original = $this->createMock(ClockInterface::class);
        $clock = new DelayedClock($original, 10);

        $original->expects($this->once())
                 ->method('now')
                 ->willReturn(\DateTimeImmutable::createFromFormat('U', '10000018'))
        ;

        $this->assertEquals(
            \DateTimeImmutable::createFromFormat('U', '10000008'),
            $clock->now(),
        );
    }
}
