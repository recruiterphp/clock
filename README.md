# Recruiter\Clock

A comprehensive clock and date/time library for PHP 8.4+, featuring testable time manipulation and MongoDB integration.

## Installation

```bash
composer require recruiterphp/clock
```

## Requirements

- PHP 8.4+
- MongoDB extension >=1.15

## Quick Start

```php
use Recruiter\Clock\SystemClock;
use Recruiter\Clock\FixedClock;
use Recruiter\DateTime\UTCDateTime;

// Production: Use system time
$clock = new SystemClock();
$now = $clock->current(); // Returns DateTime

// Testing: Use fixed time
$clock = new FixedClock(new DateTime('2024-01-01 12:00:00'));
$fixedTime = $clock->current(); // Always returns 2024-01-01 12:00:00

// UTC DateTime with microsecond precision
$utcTime = UTCDateTime::now();
echo $utcTime->toIso8601WithMicroseconds(); // 2024-01-01T12:00:00.123456+0000
```

## Core Interfaces

### Clock
Basic clock interface for getting current time:

```php
interface Clock
{
    public function current(): DateTime;
}
```

### UTCClock
Specialized interface for UTC time operations:

```php
interface UTCClock
{
    public function current(): UTCDateTime;
}
```

## Clock Implementations

### SystemClock
Uses system time:

```php
$clock = new SystemClock();
$now = $clock->current();
```

### FixedClock
Returns a fixed time (perfect for testing):

```php
$clock = new FixedClock(new DateTime('2024-01-01'));
// or
$clock = FixedClock::fromIso8601('2024-01-01T12:00:00Z');
```

### ProgressiveClock
Advances time with each call:

```php
$clock = new ProgressiveClock(
    new DateTime('2024-01-01'),
    new DateInterval('PT1H') // Advance 1 hour each call
);

$time1 = $clock->current(); // 2024-01-01 00:00:00
$time2 = $clock->current(); // 2024-01-01 01:00:00
```

### SettableClock
Allows manual time advancement:

```php
$clock = new SettableClock(new DateTime('2024-01-01'));
$clock->advance(3600); // Advance 1 hour
$later = $clock->current(); // 2024-01-01 01:00:00
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
class OrderService
{
    public function __construct(private Clock $clock) {}
    
    public function createOrder(): Order
    {
        return new Order($this->clock->current());
    }
}

// In tests
$fixedClock = new FixedClock(new DateTime('2024-01-01'));
$service = new OrderService($fixedClock);
$order = $service->createOrder();
// Order will always have 2024-01-01 timestamp
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