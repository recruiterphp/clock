<?php
namespace Recruiter\DateTime;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;
use JsonSerializable;
use MongoDB\BSON\UTCDateTime as MongoUTCDateTime;

final class UTCDateTime implements JsonSerializable, \Stringable
{
    const int MAX_SECS = 4294967296;

    private function __construct(private readonly int $sec, private readonly int $usec = 0)
    {
    }

    public function __toString(): string
    {
        return $this->sec . ' ' . $this->usec;
    }

    public function toMongoUTCDateTime(): MongoUTCDateTime
    {
        return new MongoUTCDateTime(
            intval($this->sec * 1000 + (int) round($this->usec / 1000))
        );
    }

    public function toDateTime(?DateTimeZone $timeZone = null): DateTime|false
    {
        if (is_null($timeZone)) {
            $timeZone = new DateTimeZone("UTC");
        }
        $timestamp = $this->sec . '.' . str_pad($this->usec, 6, '0', STR_PAD_LEFT);
        $date = DateTime::createFromFormat(
            "U.u",
            $timestamp
        );
        if ($date) {
            $date->setTimeZone($timeZone);
        }

        return $date;
    }

    public function toDateTimeImmutable(?DateTimeZone $timeZone = null): DateTimeImmutable
    {
        return DateTimeImmutable::createFromMutable($this->toDateTime($timeZone));
    }

    public function toIso8601WithMilliseconds(): string
    {
        $isoRepresentation = $this->toDateTime()
            ->format(DateTime::ISO8601) ;
        return $this->insertSubseconds($isoRepresentation, $this->usec / 1000, 3);
    }

    public function toIso8601WithMicroseconds(): string
    {
        $isoRepresentation = $this->toDateTime()
            ->format(DateTime::ISO8601) ;
        return $this->insertSubseconds($isoRepresentation, $this->usec, 6);
    }

    private function insertSubseconds(string $isoRepresentation, int $subseconds, int $padding): string
    {
        return str_replace(
            '+',
            '.' . sprintf("%0{$padding}d", $subseconds) . '+',
            $isoRepresentation
        );
    }

    public function toIso8601(): string
    {
        return $this->toDateTime()->format(DateTime::ISO8601);
    }

    public function toIso8601Day(): string
    {
        return $this->toDateTime()->format('Y-m-d');
    }

    public function toCondensedIso8601(): string
    {
        $roundedValue = round($this-> sec + ($this->usec / 1000 / 1000));
        return (new DateTime("@{$roundedValue}"))->format('YmdHis');
    }

    public function toApiFormat(): string
    {
        return $this->toCondensedIso8601();
    }

    public function sec(): int
    {
        return $this->sec;
    }

    public function usec(): int
    {
        return $this->usec;
    }

    public static function box($dateToBox)
    {
        if (is_null($dateToBox) || $dateToBox instanceof self) {
            return $dateToBox;
        }

        if (is_string($dateToBox)) {
            return self::fromString($dateToBox);
        }

        if (!is_object($dateToBox)) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s is not a valid value to box',
                    var_export($dateToBox, true)
                )
            );
        }

        if ($dateToBox instanceof MongoUTCDateTime) {
            $msec = intval((string)$dateToBox);

            return new self(
                (int) ($msec / 1000),
                1000 * ($msec % 1000)
            );
        }

        $clonedDateToBox = clone $dateToBox;

        if ($clonedDateToBox instanceof DateTimeInterface) {
            $usec = (int) $clonedDateToBox->format('u');
            return new self($clonedDateToBox->getTimestamp(), $usec);
        }
    }

    public static function fromStringAndtimezone($string, DateTimeZone $timeZone)
    {
        $pieces = explode('.', (string) $string);

        switch (count($pieces)) {
            case 1:
                return self::box(new DateTime($string, $timeZone));
            case 2:
                [$dateTime, $fractional] = $pieces;
                $padded = str_pad($fractional, 6, '0', STR_PAD_RIGHT);

                return self::box(new DateTime($dateTime, $timeZone))
                        ->withUsec((int)$padded);
            default:
                throw new InvalidArgumentException(
                    "expected ISO8601 with/without one fractional part separated by dot, got " . var_export($string, true)
                );
        }
    }

    public static function fromString($string)
    {
        return self::fromStringAndtimezone($string, new DateTimeZone('UTC'));
    }

    public static function fromHourlyPrecision($string)
    {
        if (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}$/', (string) $string)) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s is not a valid hourly precision string',
                    var_export($string, true)
                )
            );
        }

        return self::fromString($string . ':00');
    }

    public static function fromTimestamp(int $timestamp): self
    {
        return new self($timestamp);
    }

    public static function now(): self
    {
        return self::fromMicrotime(microtime());
    }

    public static function fromMicrotime(string $microtimeString): self
    {
        [$usec, $sec] = explode(" ", $microtimeString);
        $usec = floatval($usec);
        if ($usec >= 1) {
            throw new \InvalidArgumentException("usec parameter can’t be more than 1 second: {$usec}");
        }
        return new self(intval($sec), intval(round($usec * 1000 * 1000)));
    }

    public static function fromFloat(float $timeInSeconds): self
    {
        $sec = floor($timeInSeconds);
        $usec = $timeInSeconds - $sec;
        return new self((int) $sec, intval($usec * 1000 * 1000));
    }

    public static function fromZeroBasedDayOfYear(int $year, int $days): self
    {
        $d = DateTime::createFromFormat('Y-m-d H:i:s', "$year-01-01 00:00:00", new DateTimeZone("UTC"));

        return self::box($d)->addDays($days)->startOfDay();
    }

    public static function fromOneBasedDayOfYear($year, $days): self
    {
        return self::fromZeroBasedDayOfYear($year, $days - 1);
    }

    public static function fromIso8601($formattedString): self
    {
        return self::fromString($formattedString);
    }

    public static function fromApiFormat($formattedString): self
    {
        return self::fromString($formattedString);
    }

    public static function minimum(): self
    {
        return new self(0);
    }

    public static function maximum(): self
    {
        return new self(self::MAX_SECS);
    }

    public function subtractSeconds($seconds): self
    {
        return $this->addSeconds(-$seconds);
    }

    public function addSeconds($seconds): self
    {
        if ($this->sec + $seconds > self::MAX_SECS) {
            $sec = self::MAX_SECS;
        } elseif ($this->sec + $seconds < 0) {
            $sec = 0;
        } else {
            $sec = $this->sec + $seconds;
        }

        return new self($sec, $this->usec);
    }

    public function add(DateInterval $interval): self
    {
        $newDateTime = $this->toDateTime();
        $newDateTime->add($interval);
        return self::box($newDateTime);
    }

    public function addMonths($months): self
    {
        return $this->add(new DateInterval(sprintf('P%dM', $months)));
    }

    public function subtractMonths($months): self
    {
        return $this->sub(new DateInterval(sprintf('P%dM', $months)));
    }

    public function addDays($days): self
    {
        return $this->add(new DateInterval(sprintf('P%dD', $days)));
    }

    public function subtractDays($days): self
    {
        return $this->sub(new DateInterval(sprintf('P%dD', $days)));
    }

    public function addHours($hours): self
    {
        return $this->add(new DateInterval(sprintf('PT%dH', $hours)));
    }

    public function subtractHours($hours): self
    {
        return $this->sub(new DateInterval(sprintf('PT%dH', $hours)));
    }

    public function sub(DateInterval $interval): self
    {
        $newDateTime = $this->toDateTime();
        $newDateTime->sub($interval);
        return self::box($newDateTime);
    }

    public function startOfDay(): self
    {
        $newDateTime = $this->toDateTime();
        $newDateTime->setTime(0, 0, 0);
        return self::box($newDateTime);
    }

    public function endOfDay(): self
    {
        $newDateTime = $this->toDateTime();
        $newDateTime->setTime(23, 59, 59);
        return self::box($newDateTime);
    }

    public function startOfHour(): self
    {
        $newDateTime = $this->toDateTime();
        $newDateTime->setTime($newDateTime->format('H'), 0, 0);
        return self::box($newDateTime);
    }

    public function startOfNextHour(): self
    {
        return $this
            ->add(new DateInterval('PT1H'))
            ->startOfHour();
    }

    public function differenceInSeconds(UTCDateTime $another)
    {
        return $this->sec + $this->usec / 1000000
            - $another->sec - $another->usec / 1000000;
    }

    public function greaterThan(UTCDateTime $another)
    {
        return $this->toDateTime() > $another->toDateTime();
    }

    public function greaterThanOrEqual(UTCDateTime $another)
    {
        return self::sort($this, $another) >= 0;
    }

    public function lessThanOrEqual(UTCDateTime $another)
    {
        return self::sort($this, $another) <= 0;
    }

    public function lessThan(UTCDateTime $another)
    {
        return self::sort($this, $another) < 0;
    }

    public static function sort($a, $b)
    {
        if ($a->sec() == $b->sec() && $a->usec() == $b->usec()) {
            return 0;
        }
        if ($a->sec() == $b->sec()) {
            return $a->usec() < $b->usec() ? -1 : 1;
        } else {
            return $a->sec() < $b->sec() ? -1 : 1;
        }
    }

    public function toHourlyPrecision(): string
    {
        return $this->toDateTime()->format('Y-m-d H');
    }

    public function toHour(): string
    {
        return $this->toDateTime()->format('H');
    }

    public function toWeek(): string
    {
        return $this->toDateTime()->format('Y-W');
    }

    public function toYearMonth(): string
    {
        return $this->toDateTime()->format('Y-m');
    }

    public function toSecondPrecision(): string
    {
        return $this->toDateTime()->format('Y-m-d H:i:s');
    }

    public function withUsec(int $usec): UTCDateTime
    {
        if ($usec < 0 || $usec > 999999) {
            throw new \InvalidArgumentException(
                "usecs must be within 0 and 999999, got " . var_export($usec, true)
            );
        }

        return new self(
            $this->sec(),
            $usec
        );
    }

    public function startOfMonth()
    {
        return self::box(
            $this->toYearMonth() . '-01'
        );
    }

    public function diff(UTCDateTime $another): DateInterval|false
    {
        return $this->toDateTime()->diff($another->toDateTime());
    }

    public function jsonSerialize(): string
    {
        return $this->toIso8601WithMicroseconds();
    }

    public function __debugInfo()
    {
        return ['ISO' => $this->toIso8601WithMicroseconds()];
    }
}
