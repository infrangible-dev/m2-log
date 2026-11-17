# Changelog

## 3.0.0

### Breaking changes

- Requires Magento 2.4.6 or newer and PHP 8.2 or newer. Magento 2.1 to 2.4.5 and PHP 8.0 and 8.1 are no longer
  supported; use version 2.x for these.
- Requires `infrangible/m2-foundation` 3.0.
- The constructor of the wrapper uses constructor property promotion.

### Added

- `getLoggers()` returns the loggers of the wrapper.

### Fixed

- The constructor declares the default logger explicitly nullable; PHP 8.4 deprecates implicitly nullable parameters.
- A logger that logs while the loggers are initialized no longer initializes them again.
- The wrapper ignores itself when it is added as a logger, which logged every record endlessly.

### Changed

- Checks with PHPStan level 9, the Magento coding standard and unit tests on every push.
