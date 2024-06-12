# Changelog

## Next

- Dropped pre PHP 8.2 support
- Updated dependencies
  - `psr/http-message`: `^1.0` -> `^2.0.0`
  - `phpunit/phpunit`: `^7.0 || ^8.0 || ^9.0` -> `^9.0 || ^10.0 || ^11.0`
  - `phpstan/phpstan`: `^1.4` -> `^1.11.4`
  - `friendsofphp/php-cs-fixer`: `^v3.8.0` -> `^v3.58.1`
- Removed `tests/autload.php` and use `vendor/autoload.php` instead.
- Updated code/tests to support PHP 8.2

## 2.0.0 (22 Mar 2022)

- Added PHP8 support
- Dropped pre PHP 7.4 support
- Added SCA

## 1.0.1 (21 Aug 2019)

Bugfixes
- InputStream: getSize returns the content of the `$_SERVER['CONTENT_LENGTH']` variable. 
- OutputStream: getSize throws a exception since the size can not be determined.

## 1.0.0 (21 Aug 2019)

Initial release
