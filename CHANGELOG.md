# Changelog

## [4.0.0] - 2026-10-26

- Fixed comparison bug where files were skipped if paths started with `./` by always using `realpath()` for comparison.
- Updated requirements to PHP 8.5, modernized codestyling. Added dev tooling.

## [3.1.0] - 2024-01-29

Forked from original at https://github.com/suin/phpcs-psr4-sniff/

### Fixed
- Added compatibility with slevomat/coding-standard version 8 (https://github.com/suin/phpcs-psr4-sniff/pull/5) 
- Fixed deprecated use of ${var} (https://github.com/suin/phpcs-psr4-sniff/pull/6)

### Changed
- Updated Composer namespacing to PSR4
		