# Changelog

## Next Version
Changes
- Updated `.travis.yml` to test also with `--prefer-lowest --prefer-stable`. Thanks to [@peter279k](https://github.com/peter279k) / [PR#2](https://github.com/mintware-de/streams/pull/2)

## 1.0.1 (21 Aug 2019)

Bugfixes
- InputStream: getSize returns the content of the `$_SERVER['CONTENT_LENGTH']` variable. 
- OutputStream: getSize throws a exception since the size can not be determined.

## 1.0.0 (21 Aug 2019)

Initial release
