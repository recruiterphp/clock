<?php

declare(strict_types=1);

namespace Recruiter\Clock;

use PHPUnit\Framework\TestCase;

abstract class ClockTestCase extends TestCase
{
    protected function assertDateTimeEquals(
        \DateTimeInterface $expected,
        \DateTimeInterface $actual,
        string $message = '',
    ): void {
        $this->assertEquals($expected->getTimestamp(), $actual->getTimestamp(), $message);
        $this->assertEquals($expected->getTimezone(), $actual->getTimezone(), $message);
    }
}
