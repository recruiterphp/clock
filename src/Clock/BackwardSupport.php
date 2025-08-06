<?php

declare(strict_types=1);

namespace Recruiter\Clock;

trait BackwardSupport
{
    abstract public function now(): \DateTimeImmutable;

    public function current(): \DateTime
    {
        return \DateTime::createFromImmutable($this->now());
    }
}
