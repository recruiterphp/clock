# Recruiter\Clock

A comprehensive clock and date/time library for PHP 8.4+, featuring Symfony Clock integration, testable time manipulation, and native MongoDB support.

## Installation

```bash
composer require recruiterphp/clock
```

## Requirements

- PHP 8.4+
- MongoDB extension >=1.15
- Symfony Clock ^7.3

## Quick Start

```php
use Recruiter\Clock\ManualClock;
use Recruiter\Clock\SystemClock;
use Recruiter\DateTime\UTCDateTime;

// Production: Use system time
$clock = new SystemClock();
$now = $clock->now(); // Returns DateTimeImmutable

// Testing: Use fixed time (DateTime gets converted to DateTimeImmutable)
$clock = new ManualClock(new \DateTimeImmutable('2024-01-01 12:00:00'));
$fixedTime = $clock->now(); // Always returns 2024-01-01 12:00:00

// UTC DateTime with microsecond precision
$utcTime = UTCDateTime::now();
echo $utcTime->toIso8601WithMicroseconds(); // 2024-01-01T12:00:00.123456+0000

// MongoDB integration
$mongoClock = $clock->asMongoUTC();
$bsonDateTime = $mongoClock->now(); // Returns MongoDB\BSON\UTCDateTime
```

## Core Interfaces

### Clock
Primary clock interface extending Symfony's ClockInterface:

```php
use Symfony\Component\Clock\ClockInterface;

interface Clock extends ClockInterface
{
    // Methods from Symfony's ClockInterface:
    // - now(): \DateTimeImmutable
    // - sleep(float|int $seconds): void
    // - withTimeZone(\DateTimeZone|string $timezone): static
    
    // Additional methods for specialized clocks:
    public function asUTC(): UTCClock;
    public function asMongoUTC(): MongoUTCClock;
    public function asMicrotime(): MicrotimeClock;
    public function stopWatch(): StopWatch;
}
```

### UTCClock
Specialized interface for custom UTC DateTime operations:

```php
interface UTCClock
{
    public function now(): UTCDateTime;
}
```

### MongoUTCClock
Native MongoDB UTC DateTime interface:

```php
interface MongoUTCClock
{
    public function now(): MongoDB\BSON\UTCDateTime;
}
```

## Clock Implementations

### SystemClock
Production clock using system time (wraps Symfony's NativeClock):

```php
$clock = new SystemClock();
$now = $clock->now(); // Returns current DateTimeImmutable

// With timezone
$nyClock = new SystemClock('America/New_York');
```

### ManualClock
Test clock with manual time control (wraps Symfony's MockClock):

```php
// You can pass either DateTime or DateTimeImmutable - both work
$clock = new ManualClock(new \DateTimeImmutable('2024-01-01'));
// or use DateTime (gets converted internally to DateTimeImmutable)
$clock = new ManualClock(new \DateTime('2024-01-01'));
// or use the factory method
$clock = ManualClock::fromIso8601('2024-01-01T12:00:00Z');

// Advance time
$clock->advance(3600); // Advance 1 hour (seconds)
$clock->advance(new \DateInterval('P1D')); // Advance 1 day
```

### ProgressiveClock
Auto-advancing clock that increments on each call:

```php
$clock = new ProgressiveClock(
    new \DateTimeImmutable('2024-01-01'),
    new \DateInterval('PT1H') // Advance 1 hour each call
);

$time1 = $clock->now(); // 2024-01-01 00:00:00
$time2 = $clock->now(); // 2024-01-01 01:00:00
```

### SettableClock
Switchable clock that can override any base clock:

```php
$baseClock = new SystemClock();
$clock = new SettableClock($baseClock);

// Override with fixed time
$clock->nowIs(new \DateTimeImmutable('2024-01-01'));
$fixed = $clock->now(); // 2024-01-01

// Reset to base clock
$clock->reset();
$system = $clock->now(); // Current system time
```

### DelayedClock
Clock that returns time in the past:

```php
$baseClock = new SystemClock();
$clock = new DelayedClock($baseClock, 3600); // 1 hour delay
$past = $clock->now(); // Returns time from 1 hour ago
```

## UTCDateTime

High-precision UTC datetime with MongoDB integration:

```php
// Create from various sources
$utc = UTCDateTime::now();
$utc = UTCDateTime::fromString('2024-01-01T12:00:00Z');
$utc = UTCDateTime::fromTimestamp(1704110400);
$utc = UTCDateTime::fromMicrotime(microtime());

// Format output
echo $utc->toIso8601();                    // 2024-01-01T12:00:00+0000
echo $utc->toIso8601WithMicroseconds();    // 2024-01-01T12:00:00.123456+0000
echo $utc->toApiFormat();                  // 20240101120000

// MongoDB integration
$mongoDate = $utc->toMongoUTCDateTime();

// Date arithmetic
$later = $utc->addHours(2);
$earlier = $utc->subtractDays(1);
$tomorrow = $utc->add(new DateInterval('P1D'));

// Comparisons
$utc1->greaterThan($utc2);
$utc1->lessThanOrEqual($utc2);
```

## Date Ranges

Work with date ranges and iterations:

```php
use Recruiter\DateTime\UTCDateTimeRange;

$start = UTCDateTime::fromString('2024-01-01');
$end = UTCDateTime::fromString('2024-01-31');

$range = UTCDateTimeRange::fromTo($start, $end);

// Iterate by days
foreach ($range->dailyIterator() as $day) {
    echo $day->toIso8601Day(); // 2024-01-01, 2024-01-02, etc.
}

// Iterate by hours
foreach ($range->hourlyIterator() as $hour) {
    echo $hour->toIso8601(); // Every hour in the range
}
```

## Testing

Perfect for testing time-dependent code:

```php
use Recruiter\Clock\Clock;
use Recruiter\Clock\ManualClock;

class OrderService
{
    public function __construct(private Clock $clock) {}
    
    public function createOrder(): Order
    {
        return new Order($this->clock->now());
    }
}

// In tests
$testClock = new ManualClock(new \DateTimeImmutable('2024-01-01'));
$service = new OrderService($testClock);
$order = $service->createOrder();
// Order will always have 2024-01-01 timestamp

// Advance time to test time-based logic
$testClock->advance(3600); // 1 hour later
$laterOrder = $service->createOrder();
```

## Development

### Docker Environment

```bash
# Build and start development environment
make build
make up

# Run tests
make test

# Open bash shell inside PHP container
make shell

# View logs
make logs

# Clean up
make clean
```