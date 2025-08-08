# Recruiter\Clock 5.0.0

## 🚀 Breaking Changes

- **BREAKING**: Replaced `psr/clock` dependency with **Symfony Clock** (`symfony/clock: ^7.3`)
- **BREAKING**: Removed deprecated `current()` method from Clock interface - use `now()` instead
- **BREAKING**: Major refactoring of clock implementations to integrate with Symfony Clock framework
- **BREAKING**: Replaced integer constants with **type-safe enums**:
  - `UTCDateTimeRange::LESS_THAN` → `ComparisonOperator::LessThan`
  - `UTCDateTimeRange::LESS_THAN_EQUALS` → `ComparisonOperator::LessThanOrEquals`
  - `UTCDateTimeRange::ASCENDING` → `Direction::Ascending`
  - `UTCDateTimeRange::DESCENDING` → `Direction::Descending`

## ✨ New Features

- **Symfony Clock Integration** - All clock implementations now support Symfony Clock methods:
  - `sleep(float|int $seconds)` - Sleep functionality with time advancement for test clocks
  - `withTimeZone(\DateTimeZone|string $timezone)` - Timezone-aware clock creation
- **Enhanced PSR Clock Support** - New `PsrMicrotimeClock` adapter for existing PSR Clock implementations
- **Docker Development Improvements**:
  - **XDebug support** with dedicated `dev` Docker target
  - **Test coverage reporting** with `make test-coverage` command
  - Enhanced development workflow with better container setup

## 🛠️ Improvements

- **Enhanced Type Safety** with new enum-based APIs replacing magic constants
- **Comprehensive Test Suite** - Added extensive test coverage for all clock implementations
- **Streamlined Development Workflow**:
  - Simplified Docker configuration with multi-stage builds
  - Better Makefile with coverage support
  - Improved CI/CD with dependency caching
- **Code Modernization**:
  - Simplified constructor logic in `ProgressiveClock`
  - Enhanced `FixedClock` to prevent mutation from external DateTime objects
  - Better separation of concerns with new trait `SymfonySupport`

## 📦 Dependencies

- **Replaced** `psr/clock: ^1.0` with `symfony/clock: ^7.3`
- **Enhanced** development dependencies for better testing and code quality

## 🔧 API Changes

### Core Clock Interface

All clock implementations now extend Symfony's `ClockInterface` and provide unified access to specialized clock types:

```php
use Recruiter\Clock\SystemClock;

$clock = new SystemClock();

// Primary interface - Symfony Clock compliant
$dateTimeImmutable = $clock->now(); // Returns DateTimeImmutable

// Access specialized clock implementations
$utcClock = $clock->asUTC(); // Returns UTCClock for MongoDB integration
$microtimeClock = $clock->asMicrotime(); // Returns MicrotimeClock for float timestamps
$stopWatch = $clock->stopWatch(); // Returns StopWatch for timing operations

// NEW: Symfony Clock methods
$clock->sleep(1.5); // Sleep for 1.5 seconds (advances test clocks)
$utcClock = $clock->withTimeZone('UTC'); // Create timezone-aware variant
```

### Available Clock Implementations

- **`SystemClock`** - Production clock using system time (wraps Symfony `NativeClock`)
- **`ManualClock`** - Test clock with manual time control (wraps Symfony `MockClock`)
- **`SettableClock`** - Switchable clock that can override any base clock with fixed time
- **`ProgressiveClock`** - Auto-advancing clock that increments on each call
- **`DelayedClock`** - Clock with configurable delay offset

### Automatic Clock Wrapping

All clock implementations automatically provide access to specialized interfaces through the `AbstractClock` base class:

- **`asUTC()`** → `PsrUTCClock` - Wraps any clock for custom `UTCDateTime` operations (battle-tested for MongoDB-heavy apps)
- **`asMicrotime()`** → `PsrMicrotimeClock` - Wraps any clock for float timestamp operations
- **`stopWatch()`** → `ClockStopWatch` - Wraps any clock for elapsed time measurements

This ensures consistent access to specialized datetime handling, microtime operations, and timing functionality across all clock implementations.

**Enum Migration Example:**
```php
// Before v5.0.0
if ($range->direction() === UTCDateTimeRange::ASCENDING) { ... }
if ($range->toOperator() === UTCDateTimeRange::LESS_THAN) { ... }

// v5.0.0+
if ($range->direction() === Direction::Ascending) { ... }
if ($range->toOperator() === ComparisonOperator::LessThan) { ... }
```

## 🔄 Migration Guide

1. **Update dependencies** - Replace `psr/clock` with `symfony/clock: ^7.3`
2. **Replace deprecated `current()` calls** - Update all `$clock->current()` to `$clock->now()`
3. **Update enum usage** - Replace integer constants with new enum values:
   - Import `use Recruiter\DateTime\{ComparisonOperator, Direction};`
   - Update comparisons to use enum values instead of class constants
4. **Optional: Leverage new Symfony Clock features** like `sleep()` and `withTimeZone()`

**⚠️ Breaking Change:** The deprecated `current()` method has been removed. All code must use `now()` instead.

## Full Changelog

https://github.com/recruiterphp/clock/compare/v4.2.0...v5.0.0
