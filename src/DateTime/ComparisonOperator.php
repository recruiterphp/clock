<?php

declare(strict_types=1);

namespace Recruiter\DateTime;

enum ComparisonOperator
{
    case LessThan;
    case LessThanOrEquals;

    public function toMongoOperator(): string
    {
        return match ($this) {
            self::LessThan => '$lt',
            self::LessThanOrEquals => '$lte',
        };
    }

    public function toOperatorParenthesis(): string
    {
        return match ($this) {
            self::LessThan => ')',
            self::LessThanOrEquals => ']',
        };
    }

    public function dateComparator(): \Closure
    {
        return match ($this) {
            self::LessThan => fn (UTCDateTime $x, UTCDateTime $y) => $x < $y,
            self::LessThanOrEquals => fn (UTCDateTime $x, UTCDateTime $y) => $x <= $y,
        };
    }
}
