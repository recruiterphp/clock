<?php

declare(strict_types=1);

namespace Recruiter\DateTime;

use MongoDB\BSON\UTCDateTime as MongoUTCDateTime;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(UTCDateTimeRange::class)]
class UTCDateTimeRangeTest extends TestCase
{
    public function testItCanBuildAClosedInterval(): void
    {
        $range = UTCDateTimeRange::fromIncludedToIncluded(
            UTCDateTime::box('1985-05-21'),
            UTCDateTime::box('2015-05-21'),
        );

        $this->assertEquals(
            [
                '$gte' => new MongoUTCDateTime(485481600000),
                '$lte' => new MongoUTCDateTime(1432166400000),
            ],
            $range->toMongoDBQuery(),
        );
    }

    public function testItCanBuildARightOpenInterval(): void
    {
        $range = UTCDateTimeRange::fromIncludedToExcluded(
            UTCDateTime::box('1985-05-21'),
            UTCDateTime::box('2015-05-21'),
        );

        $this->assertEquals(
            [
                '$gte' => new MongoUTCDateTime(485481600000),
                '$lt' => new MongoUTCDateTime(1432166400000),
            ],
            $range->toMongoDBQuery(),
        );
    }

    public function testToMongoQueryOnFieldShouldReturnTheSameQueryTheNotParameterizedVersion(): void
    {
        $range = UTCDateTimeRange::fromIncludedToIncluded(
            UTCDateTime::box('1985-05-21'),
            UTCDateTime::box('2015-05-21'),
        );

        $this->assertEquals(
            [
                'goofy' => [
                    '$gte' => new MongoUTCDateTime(485481600000),
                    '$lte' => new MongoUTCDateTime(1432166400000),
                ],
            ],
            $range->toMongoQueryOnField('goofy'),
        );
    }

    public function testItCanExposeFrom(): void
    {
        $range = UTCDateTimeRange::fromIncludedToExcluded(
            $from = UTCDateTime::box('1985-05-21 10:00'),
            UTCDateTime::box('2015-05-21 12:00'),
        );

        $this->assertEquals($from, $range->from());
    }

    public function testItCanBeConvertedInApiFormat(): void
    {
        $range = UTCDateTimeRange::fromIncludedToExcluded(
            UTCDateTime::box('2015-01-01'),
            UTCDateTime::box('2015-01-02'),
        );

        $this->assertEquals('20150101000000..20150102000000', $range->toApiFormat());
    }

    public function testHourExcludedRangeGenerator(): void
    {
        $range = UTCDateTimeRange::fromIncludedToExcluded(
            UTCDateTime::box('2015-01-01 03:00'),
            UTCDateTime::box('2015-01-01 05:00'),
        );

        $this->assertEquals(
            [
                UTCDateTime::box('2015-01-01 03:00'),
                UTCDateTime::box('2015-01-01 04:00'),
            ],
            iterator_to_array($range->iteratorOnHours()),
        );
    }

    public function testHourIncludedRangeGenerator(): void
    {
        $range = UTCDateTimeRange::fromIncludedToIncluded(
            UTCDateTime::box('2015-01-01 03:00'),
            UTCDateTime::box('2015-01-01 05:00'),
        );

        $this->assertEquals(
            [
                UTCDateTime::box('2015-01-01 03:00'),
                UTCDateTime::box('2015-01-01 04:00'),
                UTCDateTime::box('2015-01-01 05:00'),
            ],
            iterator_to_array($range->iteratorOnHours()),
        );
    }

    public function testDayExcludedRangeGenerator(): void
    {
        $range = UTCDateTimeRange::fromIncludedToExcluded(
            UTCDateTime::box('2015-01-01 03:00'),
            UTCDateTime::box('2015-01-05 03:00'),
        );

        $this->assertEquals(
            [
                UTCDateTime::box('2015-01-01 03:00'),
                UTCDateTime::box('2015-01-03 03:00'),
            ],
            iterator_to_array($range->iterateOnDays(2)),
        );
    }

    public function testDayIncludedRangeGenerator(): void
    {
        $range = UTCDateTimeRange::fromIncludedToIncluded(
            UTCDateTime::box('2015-01-01 03:00'),
            UTCDateTime::box('2015-01-03 05:00'),
        );

        $this->assertEquals(
            [
                UTCDateTime::box('2015-01-01 03:00'),
                UTCDateTime::box('2015-01-02 03:00'),
                UTCDateTime::box('2015-01-03 03:00'),
            ],
            iterator_to_array($range->iterateOnDays()),
        );
    }

    public function testMonthExcludedRangeGenerator(): void
    {
        $range = UTCDateTimeRange::fromIncludedToExcluded(
            UTCDateTime::box('2015-01-01 03:00'),
            UTCDateTime::box('2015-05-01 03:00'),
        );

        $this->assertEquals(
            [
                UTCDateTime::box('2015-01-01 03:00'),
                UTCDateTime::box('2015-03-01 03:00'),
            ],
            iterator_to_array($range->iterateOnMonths(2)),
        );
    }

    public function testMonthIncludedRangeGenerator(): void
    {
        $range = UTCDateTimeRange::fromIncludedToIncluded(
            UTCDateTime::box('2015-01-01 03:00'),
            UTCDateTime::box('2015-04-01 05:00'),
        );

        $this->assertEquals(
            [
                UTCDateTime::box('2015-01-01 03:00'),
                UTCDateTime::box('2015-02-01 03:00'),
                UTCDateTime::box('2015-03-01 03:00'),
                UTCDateTime::box('2015-04-01 03:00'),
            ],
            iterator_to_array($range->iterateOnMonths()),
        );
    }

    /**
     * @return array<int,array<UTCDateTimeRange|string>>
     */
    public static function debugInfoExamples(): array
    {
        return [
            [
                UTCDateTimeRange::fromIncludedToIncluded(
                    UTCDateTime::box('2015-01-01 03:00:00.123456'),
                    UTCDateTime::box('2015-04-01 05:00:00.123456'),
                ),
                '[2015-01-01T03:00:00.123456+0000,2015-04-01T05:00:00.123456+0000]',
            ],
            [
                UTCDateTimeRange::fromIncludedToExcluded(
                    UTCDateTime::box('2015-01-01 03:00:00.123456'),
                    UTCDateTime::box('2015-04-01 05:00:00.123456'),
                ),
                '[2015-01-01T03:00:00.123456+0000,2015-04-01T05:00:00.123456+0000)',
            ],
        ];
    }

    #[DataProvider('debugInfoExamples')]
    public function testDebugInfo(UTCDateTimeRange $range, string $expected): void
    {
        $this->assertEquals(['ISO' => $expected], $range->__debugInfo());
    }

    public function testReverse(): void
    {
        $this->assertEquals(
            UTCDateTimeRange::fromIncludedToIncluded(
                UTCDateTime::box('2015-01-01 03:00:00.123456'),
                UTCDateTime::box('2015-04-01 05:00:00.123456'),
            ),
            UTCDateTimeRange::fromIncludedToIncluded(
                UTCDateTime::box('2015-04-01 05:00:00.123456'),
                UTCDateTime::box('2015-01-01 03:00:00.123456'),
            )->reverse(),
        );
    }

    public function testImpossibleReverse(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("can't reverse an open range");
        $this->assertEquals(
            UTCDateTimeRange::fromIncludedToExcluded(
                UTCDateTime::box('2015-01-01 03:00:00.123456'),
                UTCDateTime::box('2015-04-01 05:00:00.123456'),
            ),
            UTCDateTimeRange::fromIncludedToExcluded(
                UTCDateTime::box('2015-04-01 05:00:00.123456'),
                UTCDateTime::box('2015-01-01 03:00:00.123456'),
            )->reverse(),
        );
    }

    public function testDirection(): void
    {
        $this->assertSame(
            UTCDateTimeRange::ASCENDING,
            UTCDateTimeRange::fromIncludedToExcluded(
                UTCDateTime::box('2015-01-01 03:00:00.123456'),
                UTCDateTime::box('2015-04-01 05:00:00.123456'),
            )->direction(),
        );

        $this->assertSame(
            UTCDateTimeRange::ASCENDING,
            UTCDateTimeRange::fromIncludedToExcluded(
                UTCDateTime::box('2015-01-01 03:00:00.123456'),
                UTCDateTime::box('2015-01-01 03:00:00.123456'),
            )->direction(),
        );

        $this->assertSame(
            UTCDateTimeRange::DESCENDING,
            UTCDateTimeRange::fromIncludedToExcluded(
                UTCDateTime::box('2015-04-01 05:00:00.123456'),
                UTCDateTime::box('2015-01-01 03:00:00.123456'),
            )->direction(),
        );
    }

    /**
     * @requires extension mongodb
     */
    public function testToMongoDBQuery(): void
    {
        $range = UTCDateTimeRange::fromIncludedToIncluded(
            UTCDateTime::box('1985-05-21'),
            UTCDateTime::box('2015-05-21'),
        );

        $this->assertEquals(
            [
                '$gte' => new MongoUTCDateTime(485481600000),
                '$lte' => new MongoUTCDateTime(1432166400000),
            ],
            $range->toMongoDBQuery(),
        );
    }

    public function testItCanGiveTheMaximumRange(): void
    {
        $this->assertEquals(
            UTCDateTimeRange::fromIncludedToIncluded(
                UTCDateTime::minimum(),
                UTCDateTime::maximum(),
            ),
            UTCDateTimeRange::fromMinimumToMaximum(),
        );
    }
}
