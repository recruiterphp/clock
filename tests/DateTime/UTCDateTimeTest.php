<?php
namespace Recruiter\DateTime;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Eris;
use Eris\Generator;
use MongoDB\BSON\UTCDateTime as MongoUTCDateTime;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UTCDateTime::class)]
class UTCDateTimeTest extends TestCase
{
    use Eris\TestTrait;

    public function testBoxingUTCMongoDate(): void
    {
        $mongoDate = new MongoUTCDateTime(1466170836123);
        $dateTime = UTCDateTime::box($mongoDate);

        $this->assertEquals($mongoDate, $dateTime->toMongoUTCDateTime());
    }

    public function testBoxingDateTime(): void
    {
        $date = new DateTime();
        $dateTime = UTCDateTime::box($date);

        $output = $dateTime->toDateTime();
        $this->assertEquals($date->getTimestamp(), $output->getTimestamp());
        $this->assertEquals($date, $output);
    }

    public function testBoxingDateTimeImmutable(): void
    {
        $date = new DateTimeImmutable('2016-01-01 12:34:56 UTC');
        $dateTime = UTCDateTime::box($date);
        $output = $dateTime->toDateTime();

        $this->assertEquals($date->getTimestamp(), $output->getTimestamp());
        $this->assertEquals($date, $output);
    }

    public function testTimestampIsNotAffectedByTimezone(): void
    {
        $date = new DateTime();
        $dateTime = UTCDateTime::box($date);

        $output = $dateTime->toDateTime(new DateTimeZone('Europe/Rome'));

        $this->assertEquals($date->getTimestamp(), $output->getTimestamp());
        $this->assertEquals($date, $output);
    }

    public function testUnboxingToDateTimeImmutable(): void
    {
        $this->assertEquals(
            new DateTimeImmutable('2016-01-01 12:34:56', new DateTimeZone('UTC')),
            UTCDateTime::box('2016-01-01 12:34:56')->toDateTimeImmutable()
        );
    }

    public function testBoxingNullValueReturnsNull(): void
    {
        $this->assertNull(UTCDateTime::box(null));
    }

    public function testBoxingNonObjectNorNullThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('false is not a valid value to box');
        UTCDateTime::box(false);
    }

    /**
     * @depends testBoxingDateTime
     */
    public function testBoxingUTCDateTime(): void
    {
        $date = UTCDateTime::box(new \DateTime('now'));
        $this->assertEquals($date, UTCDateTime::box($date));
    }

    public function testBoxingDateTimeInTheApiFormat(): void
    {
        $this->assertEquals(
            UTCDateTime::fromIso8601('2014-09-01T12:01:02Z')->sec(),
            UTCDateTime::fromApiFormat('20140901120102')->sec()
        );
    }

    public function testBoxingDateTimeInTheIso8601Format(): void
    {
        $this->assertEquals(
            '2014-09-01T12:01:02+0000',
            UTCDateTime::fromIso8601('2014-09-01T12:01:02Z')->toIso8601()
        );
    }

    public function testFromStringFactoryMethod(): void
    {
        $expectedDate = UTCDateTime::fromTimestamp(0);
        $actualDate = UTCDateTime::fromString('1970-01-01');

        $this->assertEquals($expectedDate, $actualDate);
    }

    public function testTimezoneSetInTheStringOverwriteTheDefaultUtcTimeZone(): void
    {
        $expectedDate = UTCDateTime::fromString('2016-07-18T12:53:21+0000');
        $actualDate = UTCDateTime::fromString('2016-07-18T14:53:21+0200');

        $this->assertEquals($expectedDate, $actualDate);
    }

    public function testNowFactoryMethod(): void
    {
        $this->assertNotNull(UTCDateTime::now());
    }

    public function testPrecisionIsMaintainedwhenCreatedFromAMicrotimeString(): void
    {
        $this->assertEquals(
            UTCDateTime::box(new MongoUTCDateTime(1000123)),
            UTCDateTime::fromMicrotime('0.123000 1000')
        );

        $this->assertEquals(
            UTCDateTime::fromFloat("1000000001.123"),
            UTCDateTime::fromMicrotime("0.123 1000000001")
        );
    }

    public function testOverflowingMicrotimeString(): void
    {
        $this->expectException(\Exception::class);
        UTCDateTime::fromMicrotime('1 1000');
    }

    public function testFromIso8601FactoryMethod(): void
    {
        $this->assertEquals(
            new MongoUTCDateTime(1401624000000),
            UTCDateTime::fromIso8601('2014-06-01T12:00:00+0000')->toMongoUTCDateTime()
        );
    }

    public function testFromDayOfYearFactoryMethodRespectsDistanceBetweenDays(): void
    {
        $this->forAll(
            Generator\choose(2000, 2020),
            Generator\choose(0, 364),
            Generator\choose(0, 364)
        )
            ->then(function ($year, $dayOfYear, $anotherDayOfYear): void {
                $day = UTCDateTime::fromZeroBasedDayOfYear($year, $dayOfYear);
                $anotherDay = UTCDateTime::fromZeroBasedDayOfYear($year, $anotherDayOfYear);
                $this->assertEquals(
                    abs($dayOfYear - $anotherDayOfYear) * 86400,
                    abs($day->differenceInSeconds($anotherDay)),
                    "Days of the year $year: $dayOfYear, $anotherDayOfYear" . PHP_EOL
                    . "{$day->toIso8601()}, {$anotherDay->toIso8601()}"
                );
            });
    }

    public function testFromOneDayOfYearFactoryMethodRespectsDistanceBetweenDays(): void
    {
        $this->forAll(
            Generator\choose(2000, 2020),
            Generator\choose(1, 365),
            Generator\choose(1, 365)
        )
            ->then(function ($year, $dayOfYear, $anotherDayOfYear): void {
                $day = UTCDateTime::fromOneBasedDayOfYear($year, $dayOfYear);
                $anotherDay = UTCDateTime::fromOneBasedDayOfYear($year, $anotherDayOfYear);
                $this->assertEquals(
                    abs($dayOfYear - $anotherDayOfYear) * 86400,
                    abs($day->differenceInSeconds($anotherDay)),
                    "Days of the year $year: $dayOfYear, $anotherDayOfYear" . PHP_EOL
                    . "{$day->toIso8601()}, {$anotherDay->toIso8601()}"
                );
            });
    }

    public function testCanBeDumpedAsAHumanReadableString(): void
    {
        $this->assertEquals(
            "2001-09-09T01:46:40.123+0000",
            UTCDateTime::fromMicrotime("0.123000 1000000000")->toIso8601WithMilliseconds()
        );
    }

    public function testToYearMonth(): void
    {
        $this->assertEquals(
            "2001-09",
            UTCDateTime::fromString("2001-09-02 12:43:23")->toYearMonth()
        );
    }

    public function testMicrosecondsHaveAZerofillRepresentationForConsistency(): void
    {
        $this->assertEquals(
            "2001-09-09T01:46:40.000+0000",
            UTCDateTime::box("2001-09-09T01:46:40")->toIso8601WithMilliseconds()
        );
        $this->assertEquals(
            "2001-09-09T01:46:40.001+0000",
            UTCDateTime::fromMicrotime("0.001000 1000000000")->toIso8601WithMilliseconds()
        );
    }

    public function testMicrosecondsAreReportedDuringFormattingWhenAvailable(): void
    {
        $this->assertEquals(
            "2001-09-09T01:46:40.000000+0000",
            UTCDateTime::box("2001-09-09T01:46:40")->toIso8601WithMicroseconds()
        );
        $this->assertEquals(
            "2001-09-09T01:46:40.123456+0000",
            UTCDateTime::fromMicrotime("0.123456 1000000000")->toIso8601WithMicroseconds()
        );
    }

    public function testPrecisionIsKeptEvenDuringSubtractionOfSecondsOperation(): void
    {
        $this->assertEquals(
            UTCDateTime::box(new MongoUTCDateTime(985123)),
            UTCDateTime::box(new MongoUTCDateTime(1000123))->subtractSeconds(15)
        );
    }

    public function testPrecisionIsKeptEvenDuringDifferenceOfTimesOperation(): void
    {
        $this->assertEqualsWithDelta(
            14.6,
            UTCDateTime::box(new MongoUTCDateTime(1000123))
                ->differenceInSeconds(
                    UTCDateTime::box(new MongoUTCDateTime(985523))
                ),
            1e-10,
        );
    }

    public function testCanAddSeconds(): void
    {
        $this->assertEquals(
            UTCDateTime::box(new MongoUTCDateTime(1000123)),
            UTCDateTime::box(new MongoUTCDateTime(985123))->addSeconds(15)
        );
    }

    public function testCanAddHours(): void
    {
        $this->assertEquals(
            UTCDateTime::box(new DateTime('2014-01-01 02:45:00')),
            UTCDateTime::box(new DateTime('2014-01-01 01:45:00'))->addHours(1)
        );
    }

    public function testCondensedIso8601Precision(): void
    {
        $this->assertEquals(
            "20010909014640",
            UTCDateTime::fromMicrotime("0.4 1000000000")->toCondensedIso8601()
        );
        $this->assertEquals(
            "20010909014640",
            UTCDateTime::fromMicrotime("0.49999 1000000000")->toCondensedIso8601()
        );
        $this->assertEquals(
            "20010909014641",
            UTCDateTime::fromMicrotime("0.499999 1000000000")->toCondensedIso8601()
        );
        $this->assertEquals(
            "20010909014641",
            UTCDateTime::fromMicrotime("0.5 1000000000")->toCondensedIso8601()
        );
        $this->assertEquals(
            "20010909014641",
            UTCDateTime::fromMicrotime("0.9 1000000000")->toCondensedIso8601()
        );
    }

    public function testADateIntervalCanBeAdded(): void
    {
        $this->assertEquals(
            UTCDateTime::fromString('2014-09-01T13:00:00Z'),
            UTCDateTime::fromString('2014-09-01T12:00:00Z')->add(new DateInterval('PT1H'))
        );
    }

    public function testCanBeComparedWithOtherObjects(): void
    {
        $this->assertTrue(
            UTCDateTime::fromString('2014-09-01T12:00:01Z')->greaterThan(
                UTCDateTime::fromString('2014-09-01T12:00:00Z')
            )
        );

        $this->assertFalse(
            UTCDateTime::fromString('2014-09-01T12:00:00Z')->greaterThan(
                UTCDateTime::fromString('2014-09-01T12:00:00Z')
            )
        );

        $this->assertFalse(
            UTCDateTime::fromString('2014-09-01T12:00:00Z')->greaterThan(
                UTCDateTime::fromString('2014-09-01T12:00:01Z')
            )
        );

        $this->assertTrue(
            UTCDateTime::fromString('2014-09-01T12:00:00Z')->greaterThanOrEqual(
                UTCDateTime::fromString('2014-09-01T12:00:00Z')
            )
        );

        $this->assertTrue(
            UTCDateTime::fromString('2014-09-01T12:00:00.000001Z')->greaterThanOrEqual(
                UTCDateTime::fromString('2014-09-01T12:00:00Z')
            )
        );
    }

    public function testSort(): void
    {
        $this->assertEquals(
            0,
            UTCDateTime::sort(
                UTCDateTime::fromMicrotime("0.2 1000000000"),
                UTCDateTime::fromMicrotime("0.2 1000000000")
            )
        );
        $this->assertEquals(
            -1,
            UTCDateTime::sort(
                UTCDateTime::fromMicrotime("0.1 1000000000"),
                UTCDateTime::fromMicrotime("0.2 1000000000")
            )
        );
        $this->assertEquals(
            1,
            UTCDateTime::sort(
                UTCDateTime::fromMicrotime("0.2 1000000000"),
                UTCDateTime::fromMicrotime("0.1 1000000000")
            )
        );
        $this->assertEquals(
            -1,
            UTCDateTime::sort(
                UTCDateTime::fromMicrotime("0 1000000000"),
                UTCDateTime::fromMicrotime("0 1000000001")
            )
        );
        $this->assertEquals(
            1,
            UTCDateTime::sort(
                UTCDateTime::fromMicrotime("0 1000000001"),
                UTCDateTime::fromMicrotime("0 1000000000")
            )
        );
    }

    public function testSorting(): void
    {
        $actual = [
            UTCDateTime::fromString('2000-01-01'),
            UTCDateTime::fromString('2003-01-01'),
            UTCDateTime::fromString('2001-01-01')
        ];
        $expected = [
            UTCDateTime::fromString('2000-01-01'),
            UTCDateTime::fromString('2001-01-01'),
            UTCDateTime::fromString('2003-01-01')
        ];
        usort($actual, '\Recruiter\DateTime\UTCDateTime::sort');
        $this->assertEquals($expected, $actual);
    }

    public function testStartOfHour(): void
    {
        $date = UTCDateTime::fromString('2000-01-01 01:02:03');
        $roundedDate = $date->startOfHour();
        $this->assertEquals(
            UTCDateTime::fromString('2000-01-01 01:00:00'),
            $roundedDate
        );
    }

    public function testStartOfNextHour(): void
    {
        $date = UTCDateTime::fromString('2000-01-01 01:02:03');
        $roundedDate = $date->startOfNextHour();
        $this->assertEquals(
            UTCDateTime::fromString('2000-01-01 02:00:00'),
            $roundedDate
        );
    }

    public function testStartOfDay(): void
    {
        $date = UTCDateTime::fromString('2000-01-01 01:02:03');
        $roundedDate = $date->startOfDay();
        $this->assertEquals(
            UTCDateTime::fromString('2000-01-01 00:00:00'),
            $roundedDate
        );
    }

    public function testEndOfDay(): void
    {
        $date = UTCDateTime::fromString('2000-01-01 01:02:03');
        $roundedDate = $date->endOfDay();
        $this->assertEquals(
            UTCDateTime::fromString('2000-01-01 23:59:59'),
            $roundedDate
        );
    }

    public function testAddAndSubtractMonthsProperty(): void
    {
        $this
            ->forAll(Generator\nat())
            ->then(function ($months): void {
                $date = UTCDateTime::fromString('2000-01-03 00:00:00');

                $addSub = $date->addMonths($months)->subtractMonths($months);
                $subAdd = $date->subtractMonths($months)->addMonths($months);

                $this->assertEquals(
                    $date,
                    $addSub,
                    "adding and subtracting {$months} month(s) from {$date} returned {$addSub}"
                );

                $this->assertEquals(
                    $date,
                    $subAdd,
                    "subtracting and adding {$months} month(s) from {$date} returned {$subAdd}"
                );
            });
    }

    public function testAddDays(): void
    {
        $expected = UTCDateTime::fromString('2000-01-03 00:00:00');
        $date = UTCDateTime::fromString('2000-01-01 00:00:00');

        $added = $date->addDays(2);

        $this->assertEquals($expected, $added);
    }

    public function testSubtractDays(): void
    {
        $date = UTCDateTime::fromString('2000-01-03 00:00:00');
        $expected = UTCDateTime::fromString('2000-01-01 00:00:00');

        $added = $date->subtractDays(2);

        $this->assertEquals($expected, $added);
    }

    public function testSubtractHours(): void
    {
        $date = UTCDateTime::fromString('2000-01-03 10:00:00');
        $expected = UTCDateTime::fromString('2000-01-03 08:00:00');

        $added = $date->subtractHours(2);

        $this->assertEquals($expected, $added);
    }

    public function testSubtractSecondsFromMinimum(): void
    {
        $this->assertEquals(
            UTCDateTime::minimum(),
            UTCDateTime::minimum()->subtractSeconds(1)
        );
    }

    public function testAddSecondsToMaximum(): void
    {
        $this->assertEquals(
            UTCDateTime::maximum(),
            UTCDateTime::maximum()->addSeconds(1)
        );
    }

    public function testToIso8601Day(): void
    {
        $date = UTCDateTime::fromString('2000-01-03 00:00:00');
        $expected = '2000-01-03';

        $this->assertEquals($expected, $date->toIso8601Day());
    }

    public function testCanBeFormattedToHourlyPrecision(): void
    {
        $date = UTCDateTime::fromString('2000-01-03 10:00:00');
        $expected = '2000-01-03 10';

        $this->assertEquals($expected, $date->toHourlyPrecision());
    }

    public function testCanBeFormattedToHour(): void
    {
        $date = UTCDateTime::fromString('2000-01-03 10:00:00');
        $expected = '10';

        $this->assertEquals($expected, $date->toHour());
    }

    public function testCanBeBoxedFromHourlyPrecision(): void
    {
        $expected = UTCDateTime::fromString('2000-01-03 10:00:00');
        $date = '2000-01-03 10';

        $this->assertEquals($expected, UTCDateTime::fromHourlyPrecision($date));
    }

    public function testWrongHourlyPrecisionFormatThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("'2015-02-02 03:10' is not a valid hourly precision string");
        UTCDateTime::fromHourlyPrecision('2015-02-02 03:10');
    }

    public function testLessThanIsFalseOnEqualDates(): void
    {
        $date = UTCDateTime::box('2015-01-01');

        $this->assertFalse(
            $date->lessThan($date)
        );
    }

    public function testLessThanInPositiveCase(): void
    {
        $date = UTCDateTime::box('2015-01-01');

        $this->assertTrue(
            $date->subtractSeconds(1)->lessThan($date)
        );
    }

    public function testItCanSetUsec(): void
    {
        $date = UTCDateTime::box('2015-01-01');

        $this->assertEquals(
            UTCDateTime::fromMicrotime(
                '0.123456 1420070400'
            ),
            $date->withUsec(123456)
        );
    }

    public function testUsecGreaterThanRange(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('usecs must be within 0 and 999999, got 1000000');
        UTCDateTime::box('2015-01-01')
            ->withUsec(1000000);
    }

    public function testUsecLessThenRange(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('usecs must be within 0 and 999999, got -1');
        UTCDateTime::box('2015-01-01')
            ->withUsec(-1);
    }

    public function testItCanBeBoxedWithCustomTimeZone(): void
    {
        $boxed = UTCDateTime::fromStringAndTimezone(
            '2015-06-21T16:38:00',
            new DateTimeZone('Europe/Rome')
        );

        $this->assertEquals(
            UTCDateTime::box('2015-06-21T14:38:00'),
            $boxed
        );
    }

    public function testDiff(): void
    {
        $this->iterations = 1000;

        $this
            ->forAll(
                Generator\nat(),
                Generator\date(
                    new DateTime('1980-01-01'),
                    new DateTime('2020-12-31')
                )
            )
            ->then(function ($days, $datetime): void {
                $date = UTCDateTime::box($datetime);

                $addDiff = $date->addDays($days)->diff($date)->days;
                $subDiff = $date->subtractDays($days)->diff($date)->days;

                $this->assertSame(
                    $days,
                    $addDiff,
                    "adding and diffing {$days} days(s) from {$date->toIso8601()} returned {$addDiff}"
                );

                $this->assertEquals(
                    $days,
                    $subDiff,
                    "subtracting and diffing {$days} month(s) from {$date->toIso8601()} returned {$subDiff}"
                );
            });
    }

    public function testStartOfMonthWillGiveTheFirstDay(): void
    {
        $this
            ->forAll(
                Generator\date(
                    new DateTime('1980-01-01'),
                    new DateTime('2020-12-31')
                )
            )
            ->then(function (DateTime $date): void {
                $date->setTimeZone(new DateTimeZone('UTC'));
                $prefix = $date->format('Y-m');

                $this->assertEquals(
                    $prefix . '-01T00:00:00.000+0000',
                    UTCDateTime::box($date)->startOfMonth()->toIso8601WithMilliseconds()
                );
            })
        ;
    }

    public function testBoxingWithFractionalSeconds(): void
    {
        $this->assertEquals(
            UTCDateTime::box('2016-01-26 09:34:02')->withUsec(213060),
            UTCDateTime::box('2016-01-26 09:34:02.21306')
        );

        $this->assertEquals(
            UTCDateTime::box('2016-01-26 09:34:02'),
            UTCDateTime::box('2016-01-26 09:34:02.')
        );

        $this->assertEquals(
            UTCDateTime::box('2016-01-26 09:34:02'),
            UTCDateTime::box('2016-01-26 09:34:02.0')
        );

        $this->assertEquals(
            UTCDateTime::box('2016-01-26 09:34:02')->withUsec(100000),
            UTCDateTime::box('2016-01-26 09:34:02.1')
        );
    }

    public function testBoxingFractionalSecondsFormatErrors(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("expected ISO8601 with/without one fractional part separated by dot, got '2016-01-26 09:34:02.123.143'");
        UTCDateTime::box('2016-01-26 09:34:02.123.143');
    }

    public function testBoxingFractionalSecondsGreaterThanRange(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        UTCDateTime::box('2016-01-26 09:34:02.1234567');
    }

    public function testDebugInfo(): void
    {
        $iso = '2016-01-01T10:00:42.123456+0000';

        $this->assertEquals(['ISO' => $iso], UTCDateTime::box($iso)->__debugInfo());
    }

    public function testItCanBeJsonEncoded(): void
    {
        $iso = '2016-01-01T10:00:42.123456+0000';

        $this->assertEquals("\"$iso\"", json_encode(UTCDateTime::box($iso)));
    }
}
