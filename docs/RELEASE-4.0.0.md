# Recruiter\Clock 4.0.0

## 🚀 Breaking Changes

- **BREAKING**: Minimum PHP version upgraded from 7.1 to **8.4**
- **BREAKING**: MongoDB extension requirement upgraded from >=1.1 to **>=1.15**
- **BREAKING**: Dropped support for legacy `ext-mongo` (including `alcaeus/mongo-php-adapter`)

## ✨ New Features

- Added **strict typing** throughout the codebase
- Enhanced **microsecond precision** handling in `UTCDateTime`
- **Docker support** with development environment
- **Makefile** with common development tasks

## 🛠️ Improvements

- Upgraded to **PHPUnit 12.3**
- Added **Rector** for code modernization
- Migrated to **PSR-4** autoloading
- Enhanced type safety with return type declarations
- Converted classes to use **readonly properties** where applicable

## 🔄 Migration Guide

1. **Update PHP to 8.4+**
2. **Ensure MongoDB extension >=1.15** (no more `ext-mongo` support)
3. **Update dependencies** - remove any `alcaeus/mongo-php-adapter` references

The API remains largely unchanged - existing code should work with minimal modifications after updating PHP and MongoDB extension versions.

## Full Changelog

https://github.com/recruiterphp/clock/compare/3.0.2...v4.0.0
