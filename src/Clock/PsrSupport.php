<?php

declare(strict_types=1);

namespace Recruiter\Clock;

trait PsrSupport
{
    abstract public function current(): \DateTime;

    public function now(): \DateTimeImmutable
    {
        return \DateTimeImmutable::createFromMutable($this->current());
    }
}
