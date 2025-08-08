# Recruiter\Clock 4.1.0

## ✨ New Features

- **PSR-20 Clock compatibility** - All clock implementations now support the PSR-20 `ClockInterface`
- **GitHub Actions CI** - Added automated testing workflow with PHPUnit
- **Code formatting** - Added PHP-CS-Fixer configuration and `make fix-cs` command

## 🛠️ Improvements

- Enhanced type safety with `declare(strict_types=1)` across all files
- Improved code formatting and consistency
- Added PSR Clock dependency (`psr/clock: ^1.0`)
- Better development workflow with automated code formatting

## 📦 Dependencies

- Added `psr/clock: ^1.0` for PSR-20 compatibility
- Added `friendsofphp/php-cs-fixer: ^3.85` for code formatting

## 🔧 API Changes

All existing clock classes now implement both the original `Recruiter\Clock` interface and PSR-20's `ClockInterface`, providing a `now()` method that returns `DateTimeImmutable`:

```php
use Recruiter\Clock\SystemClock;

$clock = new SystemClock();

// Original interface (unchanged)
$dateTime = $clock->current(); // Returns DateTime

// PSR-20 interface (new)
$dateTimeImmutable = $clock->now(); // Returns DateTimeImmutable
```

This is **fully backward compatible** - existing code continues to work unchanged while gaining PSR-20 compatibility.

## Full Changelog

https://github.com/recruiterphp/clock/compare/v4.0.0...v4.1.0
