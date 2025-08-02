<?php
namespace Recruiter\DateTime;

use DomainException;

final readonly class UTCDateTimeRange
{
    private const int LESS_THAN = 1;
    private const int LESS_THAN_EQUALS = 2;

    public const int ASCENDING = 1;
    public const int DESCENDING = 2;

    public static function fromIncludedToExcluded(UTCDateTime $from, UTCDateTime $to): self
    {
        return new self($from, $to, self::LESS_THAN);
    }

    public static function fromIncludedToIncluded(UTCDateTime $from, UTCDateTime $to): self
    {
        return new self($from, $to, self::LESS_THAN_EQUALS);
    }

    public static function fromMinimumToMaximum(): self
    {
        return self::fromIncludedToIncluded(
            UTCDateTime::minimum(),
            UTCDateTime::maximum()
        );
    }

    private function __construct(
        private UTCDateTime $from,
        private UTCDateTime $to,
        private int $toOperator,
    ) {
    }

    public function toMongoDBQuery(): array
    {
        return [
            '$gte' => $this->from->toMongoUTCDateTime(),
            $this->mongoOperator($this->toOperator) => $this->to->toMongoUTCDateTime(),
        ];
    }

    private function mongoOperator(int $toOperator): string
    {
        return match ($toOperator) {
            self::LESS_THAN => '$lt',
            self::LESS_THAN_EQUALS => '$lte',
            // won't be reached, makes the type checker happy
            default => '',
        };
    }

    private function toOperatorParenthesis(int $toOperator): string
    {
        return match ($toOperator) {
            self::LESS_THAN => ')',
            self::LESS_THAN_EQUALS => ']',
            // won't be reached, makes the type checker happy
            default => '',
        };
    }

    public function toMongoQueryOnField(string $fieldName): array
    {
        return [$fieldName => $this->toMongoDBQuery()];
    }

    public function from(): UTCDateTime
    {
        return $this->from;
    }

    public function to(): UTCDateTime
    {
        return $this->to;
    }

    public function toOperator(): int
    {
        return $this->toOperator;
    }

    public function toApiFormat(): string
    {
        return sprintf('%s..%s', $this->from->toApiFormat(), $this->to->toApiFormat());
    }

    public function iteratorOnHours(int $increment = 1): RangeIterator
    {
        return $this->generatorWith(
            fn($dateTime) => $dateTime->addHours($increment)
        );
    }

    public function iterateOnDays(int $increment = 1): RangeIterator
    {
        return $this->generatorWith(
            fn($dateTime) => $dateTime->addDays($increment)
        );
    }

    public function iterateOnMonths(int $increment = 1): RangeIterator
    {
        return $this->generatorWith(
            fn($dateTime) => $dateTime->addMonths($increment)
        );
    }

    public function __debugInfo()
    {
        $debug = '[';
        $debug .= $this->from->toIso8601WithMicroseconds();
        $debug .= ',';
        $debug .= $this->to->toIso8601WithMicroseconds();
        $debug .= $this->toOperatorParenthesis($this->toOperator);

        return ['ISO' => $debug];
    }

    public function reverse(): self
    {
        if ($this->toOperator === self::LESS_THAN) {
            throw new DomainException("can't reverse an open range");
        }

        return new self(
            $this->to,
            $this->from,
            $this->toOperator
        );
    }

    public function direction(): int
    {
        if ($this->from->lessThanOrEqual($this->to)) {
            return self::ASCENDING;
        } else {
            return self::DESCENDING;
        }
    }

    private function generatorWith(\Closure $incrementer): RangeIterator
    {
        return new RangeIterator(
            $this->from,
            $this->to,
            $this->dateComparator(),
            $incrementer
        );
    }

    private function dateComparator(): \Closure
    {
        return match ($this->toOperator) {
            self::LESS_THAN => fn($x, $y) => $x < $y,
            // to make the type checker happy
            default => fn($x, $y) => $x <= $y,
        };
    }
}
