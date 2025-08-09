# Recruiter\Clock 5.1.0

## ✨ New Features

- **Native MongoDB support** - New `asMongoUTC()` method directly returns MongoDB's native `UTCDateTime` objects
- **Namespace reorganization** - Core interfaces moved to `Recruiter\Clock` namespace for better organization

## 🛠️ Improvements

- **Direct MongoDB integration** - Added `MongoUTCClock` interface and `PsrMongoUTCClock` wrapper for seamless MongoDB `BSON\UTCDateTime` conversion
- **Better namespace structure** - Relocated core interfaces (`Clock`, `UTCClock`, `MicrotimeClock`, `StopWatch`) to dedicated namespaces

## 🔧 API Changes

### New MongoDB Clock Access

All clock implementations now provide direct MongoDB `UTCDateTime` conversion:

```php
use Recruiter\Clock\SystemClock;

$clock = new SystemClock();

// NEW: Direct MongoDB UTCDateTime conversion
$mongoUTC = $clock->asMongoUTC(); // Returns MongoUTCClock
$bsonDateTime = $mongoUTC->now(); // Returns MongoDB\BSON\UTCDateTime

// Existing specialized clocks still available
$utcClock = $clock->asUTC(); // Custom UTCDateTime for advanced operations
$microtimeClock = $clock->asMicrotime(); // Float timestamp operations
```

This addition complements the existing `asUTC()` method, providing native MongoDB types when needed while keeping the powerful custom `UTCDateTime` class for advanced operations.

## 📦 Dependencies

No dependency changes - continues to use `symfony/clock: ^7.3`

## Full Changelog

https://github.com/recruiterphp/clock/compare/v5.0.0...v5.1.0