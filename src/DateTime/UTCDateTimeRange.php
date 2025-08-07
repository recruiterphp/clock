<?php

declare(strict_types=1);

namespace Recruiter\DateTime;

use MongoDB\BSON\UTCDateTime as MongoUTCDateTime;

final readonly class UTCDateTimeRange
{
    public static function fromIncludedToExcluded(UTCDateTime $from, UTCDateTime $to): self
    {
        return new self($from, $to, ComparisonOperator::LessThan);
    }

    public static function fromIncludedToIncluded(UTCDateTime $from, UTCDateTime $to): self
    {
        return new self($from, $to, ComparisonOperator::LessThanOrEquals);
    }

    public static function fromMinimumToMaximum(): self
    {
        return self::fromIncludedToIncluded(
            UTCDateTime::minimum(),
            UTCDateTime::maximum(),
        );
    }

    private function __construct(
        private UTCDateTime $from,
        private UTCDateTime $to,
        private ComparisonOperator $toOperator,
    ) {
    }

    /**
     * @return array<string, MongoUTCDateTime>
     */
    public function toMongoDBQuery(): array
    {
        return [
            '$gte' => $this->from->toMongoUTCDateTime(),
            $this->toOperator->toMongoOperator() => $this->to->toMongoUTCDateTime(),
        ];
    }

    /**
     * @return array<string,array<string,MongoUTCDateTime>>
     */
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

    public function toOperator(): ComparisonOperator
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
            fn (UTCDateTime $dateTime) => $dateTime->addHours($increment),
        );
    }

    public function iterateOnDays(int $increment = 1): RangeIterator
    {
        return $this->generatorWith(
            fn (UTCDateTime $dateTime) => $dateTime->addDays($increment),
        );
    }

    public function iterateOnMonths(int $increment = 1): RangeIterator
    {
        return $this->generatorWith(
            fn (UTCDateTime $dateTime) => $dateTime->addMonths($increment),
        );
    }

    /**
     * @return array<string,string>
     */
    public function __debugInfo(): array
    {
        $debug = '[';
        $debug .= $this->from->toIso8601WithMicroseconds();
        $debug .= ',';
        $debug .= $this->to->toIso8601WithMicroseconds();
        $debug .= $this->toOperator->toOperatorParenthesis();

        return ['ISO' => $debug];
    }

    public function reverse(): self
    {
        if (ComparisonOperator::LessThan === $this->toOperator) {
            throw new \DomainException("can't reverse an open range");
        }

        return new self(
            $this->to,
            $this->from,
            $this->toOperator,
        );
    }

    public function direction(): Direction
    {
        if ($this->from->lessThanOrEqual($this->to)) {
            return Direction::Ascending;
        } else {
            return Direction::Descending;
        }
    }

    /**
     * @param \Closure(UTCDateTime): UTCDateTime $incrementer
     */
    private function generatorWith(\Closure $incrementer): RangeIterator
    {
        return new RangeIterator(
            $this->from,
            $this->to,
            $this->toOperator->dateComparator(),
            $incrementer,
        );
    }
}
