# PBM Logger

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pbmengine/pbm-logger.svg?style=flat-square)](https://packagist.org/packages/pbmengine/pbm-logger)

## Intro

This package needs a Laravel application. It provides a logging channel to log exceptions and messages to PBM
 Monitoring System.

## Installation

You can install the package via composer:

```bash
composer require pbmengine/pbm-logger
```

## Usage

Add the following channel in your Laravel logging.php file:
 
``` php
// config/logging.php
'channels' => [
// ...
    'stack' => [
        'driver' => 'stack',
        'channels' => ['pbm', 'daily'],
        'ignore_exceptions' => false,
    ],

    'pbm' => [
        'driver' => 'custom',
        'via' => Pbmengine\Logger\LogFactory::class,
        'anonymize_ips' => true,
        'api_endpoint' => '<your endpoint>/api/reports',
        'api_key' => env('PBM_LOGGER_KEY'),
        'level' => Monolog\Logger::DEBUG,
    ],
// ...
]
```

Add the `PBM_LOGGER_KEY` to your .env file.

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email stefan@sriehl.com instead of using the issue tracker.

## Credits

- [Stefan Riehl](https://github.com/stefanriehl)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
