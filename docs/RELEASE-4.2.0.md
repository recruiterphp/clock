# Recruiter\Clock 4.2.0

## ✨ New Features

- **Deprecation warning** - Added `@deprecated` annotation to `Clock::current()` method, recommending PSR-20's `now()` method
- **Improved GitHub Actions** - Enhanced CI workflow with better Docker setup and dependency caching

## 🛠️ Improvements

- **Simplified Docker setup** - Consolidated Docker configuration with single-stage build
- **Streamlined Makefile** - Removed PHPStan and Rector commands, focusing on core development workflow
- **Code modernization** - Updated type hints and removed unnecessary complexity across the codebase
- **Better CI performance** - Added Composer cache and optimized build process
- **Fixed `UTCDateTime::toWeek()`** - Now correctly returns ISO week format (`o-\WW`) following the ISO 8601 standard

## 🔧 API Changes

The `Clock::current()` method is now deprecated in favor of the PSR-20 standard `now()` method:

```php
use Recruiter\Clock\SystemClock;

$clock = new SystemClock();

// Deprecated (still works but not recommended)
$dateTime = $clock->current(); // Returns DateTime

// Recommended (PSR-20 standard)
$dateTimeImmutable = $clock->now(); // Returns DateTimeImmutable
```

This change encourages migration to the PSR-20 standard while maintaining **full backward compatibility**.

## 🔄 Migration Notes

- Update usages of `current()` to `now()` when possible
- No breaking changes - existing code continues to work
- The PSR-20 `now()` method returns `DateTimeImmutable` instead of `DateTime`

## Full Changelog

https://github.com/recruiterphp/clock/compare/v4.1.0...v4.2.0
