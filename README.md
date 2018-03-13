# Dingo API customization

[![Build Status](https://travis-ci.org/Saritasa/php-dingo-api-custom.svg?branch=master)](https://travis-ci.org/Saritasa/php-dingo-api-custom)
[![codecov](https://codecov.io/gh/Saritasa/php-dingo-api-custom/branch/master/graph/badge.svg)](https://codecov.io/gh/Saritasa/php-dingo-api-custom)
[![Release](https://img.shields.io/github/release/saritasa/php-dingo-api-custom.svg)](https://github.com/Saritasa/php-dingo-api-custom/releases)
[![PHPv](https://img.shields.io/packagist/php-v/saritasa/dingo-api-custom.svg)](http://www.php.net)
[![Downloads](https://img.shields.io/packagist/dt/saritasa/dingo-api-custom.svg)](https://packagist.org/packages/saritasa/dingo-api-custom)

Custom settings and extensions for Dingo/Api package

See https://github.com/dingo/api


## Laravel 5.x

Install the ```saritasa/dingo-api-custom``` package:

```bash
$ composer require saritasa/dingo-api-custom
```

If you use Laravel 5.4 or less,
or 5.5+ with [package discovery](https://laravel.com/docs/5.5/packages#package-discovery) disabled,
add the BladeDirectivesServiceProvider service provider in ``config/app.php``:

```php
'providers' => array(
    // ...
    Saritasa\DingoApi\SaritasaDingoApiServiceProvider::class,
)
```

## Customizations

Registers **CustomArraySerializer** instead of **ArraySerializer**:

* Does not add 'meta' key for metadata, all metadata output to root.

Registers custom ApiExceptionHandler with specific handling of
exceptions, defined in [saritasa/php-common](https://github.com/Saritasa/php-common) package

Changes format of JSON output for handled validation exceptions.


## Contributing

1. Create fork, checkout it
2. Develop locally as usual. **Code must follow [PSR-1](http://www.php-fig.org/psr/psr-1/), [PSR-2](http://www.php-fig.org/psr/psr-2/)** -
    run [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) to ensure, that code follows style guides
3. **Cover added functionality with unit tests** and run [PHPUnit](https://phpunit.de/) to make sure, that all tests pass
4. Update [README.md](README.md) to describe new or changed functionality
5. Add changes description to [CHANGES.md](CHANGES.md) file. Use [Semantic Versioning](https://semver.org/) convention to determine next version number.
6. When ready, create pull request

### Make shortcuts

If you have [GNU Make](https://www.gnu.org/software/make/) installed, you can use following shortcuts:

* ```make cs``` (instead of ```php vendor/bin/phpcs```) -
    run static code analysis with [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
    to check code style
* ```make csfix``` (instead of ```php vendor/bin/phpcbf```) -
    fix code style violations with [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
    automatically, where possible (ex. PSR-2 code formatting violations)
* ```make test``` (instead of ```php vendor/bin/phpunit```) -
    run tests with [PHPUnit](https://phpunit.de/)
* ```make install``` - instead of ```composer install```
* ```make all``` or just ```make``` without parameters -
    invokes described above **install**, **cs**, **test** tasks sequentially -
    project will be assembled, checked with linter and tested with one single command

## Resources

* [Bug Tracker](http://github.com/saritasa/php-dingo-api-custom/issues)
* [Code](http://github.com/saritasa/php-dingo-api-custom)
* [Changes History](CHANGES.md)
* [Authors](http://github.com/saritasa/php-dingo-api-custom/contributors)
