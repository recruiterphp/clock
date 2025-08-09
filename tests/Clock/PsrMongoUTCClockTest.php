<?php

declare(strict_types=1);

namespace Clock;

use MongoDB\BSON\UTCDateTime;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;
use Recruiter\Clock\PsrMongoUTCClock;

#[CoversClass(PsrMongoUTCClock::class)]
#[UsesClass(UTCDateTime::class)]
class PsrMongoUTCClockTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testItCanWrapExistingPSRClock(): void
    {
        $original = $this->createMock(ClockInterface::class);
        $clock = new PsrMongoUTCClock($original);

        $original->expects($this->once())
                 ->method('now')
                 ->willReturn(new \DateTimeImmutable('1989-11-09T18:57:00 Europe/Berlin'))
        ;

        $this->assertEquals(
            new UTCDateTime(626_637_420_000),
            $clock->now(),
        );
    }
}
