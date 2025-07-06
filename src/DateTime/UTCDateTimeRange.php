<?php
namespace Recruiter\DateTime;

use DomainException;

final class UTCDateTimeRange
{
    const int LESS_THAN = 1;
    const int LESS_THAN_EQUALS = 2;

    const int ASCENDING = 1;
    const int DESCENDING = 2;

    public static function fromIncludedToExcluded(UTCDateTime $from, UTCDateTime $to)
    {
        return new self($from, $to, self::LESS_THAN);
    }

    public static function fromIncludedToIncluded(UTCDateTime $from, UTCDateTime $to)
    {
        return new self($from, $to, self::LESS_THAN_EQUALS);
    }

    public static function fromMinimumToMaximum()
    {
        return self::fromIncludedToIncluded(
            UTCDateTime::minimum(),
            UTCDateTime::maximum()
        );
    }

    private function __construct(private readonly UTCDateTime $from, private readonly UTCDateTime $to, private $toOperator)
    {
    }

    public function toMongoDBQuery()
    {
        return [
            '$gte' => $this->from->toMongoUTCDateTime(),
            $this->mongoOperator($this->toOperator) => $this->to->toMongoUTCDateTime(),
        ];
    }

    private function mongoOperator($toOperator)
    {
        switch ($toOperator) {
            case self::LESS_THAN:
                return '$lt';
            case self::LESS_THAN_EQUALS:
                return '$lte';
        }
    }

    private function toOperatorParenthesis($toOperator)
    {
        switch ($toOperator) {
            case self::LESS_THAN:
                return ')';
            case self::LESS_THAN_EQUALS:
                return ']';
        }
    }

    public function toMongoQueryOnField($fieldName)
    {
        return [$fieldName => $this->toMongoDBQuery()];
    }

    /**
     * @return UTCDateTime
     */
    public function from()
    {
        return $this->from;
    }

    /**
     * @return UTCDateTime
     */
    public function to()
    {
        return $this->to;
    }

    public function toOperator()
    {
        return $this->toOperator;
    }

    public function toApiFormat()
    {
        return sprintf('%s..%s', $this->from->toApiFormat(), $this->to->toApiFormat());
    }

    public function iteratorOnHours($increment = 1)
    {
        return $this->generatorWith(
            fn($dateTime) => $dateTime->addHours($increment)
        );
    }

    public function iterateOnDays($increment = 1)
    {
        return $this->generatorWith(
            fn($dateTime) => $dateTime->addDays($increment)
        );
    }

    public function iterateOnMonths($increment = 1)
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

    public function reverse()
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

    public function direction()
    {
        if ($this->from->lessThanOrEqual($this->to)) {
            return self::ASCENDING;
        } else {
            return self::DESCENDING;
        }
    }

    private function generatorWith(callable $incrementer)
    {
        return new RangeIterator(
            $this->from,
            $this->to,
            $this->dateComparator(),
            $incrementer
        );
    }

    private function dateComparator()
    {
        switch ($this->toOperator) {
            case self::LESS_THAN:
                return fn($x, $y) => $x < $y;
            case self::LESS_THAN_EQUALS:
                return fn($x, $y) => $x <= $y;
        }
    }
}
