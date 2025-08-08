<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;

#[CoversClass(PsrMicrotimeClock::class)]
class PsrMicrotimeClockTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testItCanWrapExistingPSRClock(): void
    {
        $original = $this->createMock(ClockInterface::class);
        $clock = new PsrMicrotimeClock($original);

        $original->expects($this->once())
                 ->method('now')
                 ->willReturn(new \DateTimeImmutable('1989-11-09T18:57:00.123456 Europe/Berlin'))
        ;

        $this->assertEquals(
            626637420.123456,
            $clock->now(),
        );
    }
}
