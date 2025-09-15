# filament-webhooks

[![Latest Version on Packagist](https://img.shields.io/packagist/v/basementdevs/filament-webhooks.svg?style=flat-square)](https://packagist.org/packages/basementdevs/filament-webhooks)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/basementdevs/filament-webhooks/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/basementdevs/filament-webhooks/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/basementdevs/filament-webhooks/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/basementdevs/filament-webhooks/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/basementdevs/filament-webhooks.svg?style=flat-square)](https://packagist.org/packages/basementdevs/filament-webhooks)

A Laravel package that adds Filament v4 resources to view and manage inbound webhooks. It provides:
- A configurable Filament navigation entry for "Webhooks" under a Logs group
- Storage/model for inbound webhook payloads
- Enum of supported webhook providers with icons/colors for the Filament UI

Note: This readme documents what is detectable from the codebase. Where details are unclear, TODO notes are added for follow-up.

## Requirements
- PHP ^8.3
- Laravel (Illuminate Contracts) ^11.0 or ^12.0
- Filament ^4.0
- Composer

Development dependencies (repo contributors): Pest, PHPStan, Larastan, Testbench, Pint

## Installation
Install via Composer in a Laravel application:

```bash
composer require basementdevs/filament-webhooks
```

The package auto-registers its service provider via the `extra.laravel.providers` entry in composer.json.

### Migrations
This package discovers and loads its migrations. Run your app migrations after installing:

```bash
php artisan migrate
```

### Configuration
Publish the config file if you need to customize defaults:

```bash
php artisan vendor:publish --tag="filament-webhooks-config"
```

Config options (config/filament-webhooks.php):
- navigation_group: default "Logs"
- navigation_label: default "Webhooks"
- navigation_icon_inactive / navigation_icon_active: Filament Heroicon enums
- navigation_sort: default 100
- model: Eloquent model class used to store inbound webhooks (defaults to Basement\Webhooks\Models\InboundWebhook)
- providers_enum: Enum class of supported providers (defaults to Basement\Webhooks\Enums\InboundWebhookSource)

### Views
If the package ships views under a publish tag, they can be published with:

```bash
php artisan vendor:publish --tag="filament-webhooks-views"
```

Note: The presence and contents of views are not confirmed in the current repo. TODO: Document available views/components.

## Usage
Once installed in a Filament-enabled Laravel application, a "Webhooks" section should appear in your Filament admin navigation (by default under the "Logs" group). From there you can review inbound webhooks stored by your application.

How inbound webhooks are stored depends on your app integration. This package provides supporting types and actions, including:
- Model: `Basement\Webhooks\Models\InboundWebhook`
- Enum: `Basement\Webhooks\Enums\InboundWebhookSource` 
- Action: `Basement\Webhooks\Actions\StoreInboundWebhook` (implementation details depend on your app)


## Running Tests
This repository uses Pest for testing:

```bash
composer test
```

To generate coverage:

```bash
composer test-coverage
```

## Changelog
See [CHANGELOG.md](CHANGELOG.md) for recent changes.

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details. If this file is missing, TODO: add contribution guidelines or update this section.

## Security Vulnerabilities
Please review [our security policy](../../security/policy) on how to report security vulnerabilities. If your repository does not include this path, TODO: add SECURITY.md and update the link.

## License
The MIT License (MIT). See [LICENSE.md](LICENSE.md) for more information.
