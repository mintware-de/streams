[![Travis](https://img.shields.io/travis/mintware-de/streams.svg)](https://travis-ci.org/mintware-de/streams)
[![Packagist](https://img.shields.io/packagist/v/mintware-de/streams.svg)](https://packagist.org/packages/mintware-de/streams)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/mintware-de/streams/master/LICENSE)
[![Packagist](https://img.shields.io/packagist/dt/mintware-de/streams.svg)](https://packagist.org/packages/mintware-de/streams)

# 💾 Streams for PHP

This package provides some implementations of the PSR-7 StreamInterface.

## 📦 Installation
You can install this package using composer:
```shell script
$ composer require mintware-de/streams
```

## 📄 FileStream
Provides read / write access for files.

## 💻 MemoryStream
With this implementation you can read data from and write data to the memory.

## 📥 InputStream
Provides read-only access for the `php://input` resource. This holds for example the raw HTTP request.

## 📤 OutputStream
Provides write-only access for the `php://output` resource.

## 🧪 Unit Tests
```shell script
$ phpunit
```

## ⭐️ Rating
Don't forget to hit the ⭐️-Star button if you find this package useful. 
Thanks 🙂