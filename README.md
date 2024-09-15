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
    'notifications' => [
        OnboardedToPaid::class => [
            PostPaymentBuyerNotification::class
        ],
    ]
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="contracts-views"
```

## Contract Concepts

### Inputs

#### Input Relations
- customer
  - gross monthly income
  - birth date
- inventory
  - total contract price
  - appraised value

#### Input Attributes
- percent down payment
- percent miscellaneous fees
- down payment term
- balance payment term
- interest rate

### Mortgage


### Contract States
- Pending
- Consulted
- Availed
- Verified
- Onboarded
- Paid, PaymentFailed
- Assigned
- Acknowledged, Idled
- Pre-qualified
- Qualified, Not Qualified
- Approved, Disapproved, Overridden
- Validated
- Cancelled

## Usage

```php
use Homeful\Properties\Models\Property as Inventory;
use Homeful\Contacts\Models\Contact as Customer;
use Homeful\Contracts\States\Consulted;
use Homeful\Contacts\Data\ContactData;
use Homeful\Contracts\Models\Contract;
use Homeful\Common\Classes\Input;
use Homeful\Mortgage\Mortgage;

$contract = new Contract;
$contract->customer = $customer;
$contract->inventory = $inventory;
$contract->percent_down_payment = $params[Input::PERCENT_DP];
$contract->percent_miscellaneous_fees = $params[Input::PERCENT_MF];
$contract->down_payment_term = $params[Input::DP_TERM];
$contract->balance_payment_term = $params[Input::BP_TERM];
$contract->interest_rate = $params[Input::BP_INTEREST_RATE];
$contract->save();
$contract->load('customer', 'inventory');

$contract->state->transitionTo(Consulted::class, reference: $reference);
$contract->mortgage instanceof \Homeful\Mortgage\Mortgage
$data = ContactData::fromModel($contract);
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
