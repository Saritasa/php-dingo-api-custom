# Dingo API customization

[![Build Status](https://travis-ci.org/Saritasa/php-dingo-api-custom.svg?branch=master)](https://travis-ci.org/Saritasa/php-dingo-api-custom)

Custom settings and extensions for Dingo/Api package

See https://github.com/dingo/api


## Laravel 5.x

Install the ```saritasa/dingo-api-custom``` package:

```bash
$ composer require saritasa/dingo-api-custom
```

If you use Laraval 5.4 or less,
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

1. Create fork
2. Checkout fork
3. Develop locally as usual. **Code must follow [PSR-1](http://www.php-fig.org/psr/psr-1/), [PSR-2](http://www.php-fig.org/psr/psr-2/)**
4. Run [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) to ensure, that code follows style guides
5. Update [README.md](README.md) to describe new or changed functionality. Add changes description to [CHANGES.md](CHANGES.md) file.
6. When ready, create pull request

## Resources

* [Bug Tracker](http://github.com/saritasa/php-dingo-api-custom/issues)
* [Code](http://github.com/saritasa/php-dingo-api-custom)
* [Changes History](CHANGES.md)
* [Authors](http://github.com/saritasa/php-dingo-api-custom/contributors)
