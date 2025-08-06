<?php

declare(strict_types=1);

namespace Clock;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;
use Recruiter\Clock\PsrUTCClock;
use Recruiter\DateTime\UTCDateTime;

#[CoversClass(PsrUTCClock::class)]
class PsrUTCClockTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testItCanWrapExistingPSRClock(): void
    {
        $original = $this->createMock(ClockInterface::class);
        $clock = new PsrUTCClock($original);

        $original->expects($this->once())
                 ->method('now')
                 ->willReturn(new DateTimeImmutable('1989-11-09T18:57:00 Europe/Berlin'));
        ;

        $this->assertEquals(
            UTCDateTime::fromTimestamp(626637420),
            $clock->current(),
        );
    }
}
