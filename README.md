# Homeful Contracts Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jn-devops/contracts.svg?style=flat-square)](https://packagist.org/packages/jn-devops/contracts)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jn-devops/contracts/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jn-devops/contracts/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jn-devops/contracts/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jn-devops/contracts/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jn-devops/contracts.svg?style=flat-square)](https://packagist.org/packages/jn-devops/contracts)

## Installation

You can install the package via composer:

```bash
composer require jn-devops/contracts
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="contracts-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="contracts-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="contracts-views"
```

## Usage

```php
$contracts = new Homeful\Contracts();
echo $contracts->echoPhrase('Hello, Homeful!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Lester B. Hurtado](https://github.com/jn-devops)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
