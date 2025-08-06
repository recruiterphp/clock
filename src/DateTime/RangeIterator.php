<?php

declare(strict_types=1);

namespace Recruiter\DateTime;

/**
 * @implements \Iterator<int,UTCDateTime>
 */
class RangeIterator implements \Iterator
{
    private int $index;
    private UTCDateTime $current;

    /**
     * @param \Closure(UTCDateTime,UTCDateTime): bool $comparator
     * @param \Closure(UTCDateTime): UTCDateTime $incrementer
     */
    public function __construct(
        private readonly UTCDateTime $from,
        private readonly UTCDateTime $to,
        private readonly \Closure $comparator,
        private readonly \Closure $incrementer,
    ) {
        $this->rewind();
    }

    public function current(): UTCDateTime
    {
        return $this->current;
    }

    public function key(): int
    {
        return $this->index;
    }

    public function next(): void
    {
        $this->current = call_user_func($this->incrementer, $this->current);
        ++$this->index;
    }

    public function rewind(): void
    {
        $this->current = clone $this->from;
        $this->index = 0;
    }

    public function valid(): bool
    {
        return call_user_func($this->comparator, $this->current, $this->to);
    }
}
