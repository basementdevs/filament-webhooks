# Changelog

All notable changes to `filament-webhooks` will be documented in this file.

## 6.0.0 - 2026-03-26

### Breaking Changes

* `StoreInboundWebhook::store()` now accepts only `array` payloads (was `array|string`)
* `StoreInboundWebhook::store()` now returns `InboundWebhook` (was `void`)
* `StoresInboundWebhook` contract added — consumers should type-hint the interface
* New `status`, `processed_at`, `error_message` columns on `inbound_webhooks` table (run migrations)
* New `webhook_delivery_attempts` table (run migrations)

### New Features

* **Status tracking**: webhooks now have a processing status (pending/processing/completed/failed) with badge column, filter, and enum
* **Replay action**: re-dispatch InboundWebhookReceived event from list or view page
* **Copy payload**: copy JSON payload to clipboard from table row or view page
* **Mark completed / Mark failed**: manual triage actions with optional error reason
* **Delivery attempts**: tracks replay history with timestamps and error messages
* **Timeline chart widget**: line chart showing webhook volume over the last 30 days
* **Cleanup command**: `php artisan webhooks:cleanup --days=30` with configurable `retention_days`
* **Navigation badge**: shows count of webhooks received in the last 24 hours
* **Source and event filters**: filter webhooks by source, event, status, or trashed
* **Searchable columns**: source and event are now searchable in the table
* **Database indexes**: source, event, created_at columns are now indexed
* **InboundWebhookReceived event**: dispatched when a webhook is stored
* **Fluent plugin API**: configure the plugin with method chaining instead of config file only

### Bug Fixes

* Fix payload display in view page (was broken due to double json_decode on array-cast column)
* Fix payload double-encoding in StoreInboundWebhook and factory (model array cast already handles encoding)
* Fix N+1 queries in stats widget (single GROUP BY query instead of 1+N)
* Fix widget ignoring configurable `providers_enum` and `model` config
* Fix `navigation_sort` default mismatch (code defaulted to 10, config declared 100)
* Fix PHPStan error in `getWidgets()` return type
* Replace hardcoded Portuguese string in widget with universal notation
* Remove PII from factory data
* Delete dead `ModelFactory.php` stub
* Fix placeholder package description in composer.json

### DX Improvements

* Test coverage: 1 test to 31 tests (66 assertions)
* Fixed and un-skipped Filament resource tests with proper TestCase setup
* Added `StoresInboundWebhook` contract bound in service provider

**Full Changelog**: https://github.com/basementdevs/filament-webhooks/compare/5.0.2...6.0.0

## 5.0.2 - 2026-03-13

**Full Changelog**: https://github.com/basementdevs/filament-webhooks/compare/5.0.1...5.0.2

## 5.0.1 - 2026-03-13

### What's Changed

* feat: adding flag to see resource by @RichardGL11 in https://github.com/basementdevs/filament-webhooks/pull/10

### New Contributors

* @RichardGL11 made their first contribution in https://github.com/basementdevs/filament-webhooks/pull/10

**Full Changelog**: https://github.com/basementdevs/filament-webhooks/compare/5.0.0...5.0.1

## 5.0.0 - 2026-03-02

### What's Changed

* chore(deps): bump dependabot/fetch-metadata from 2.4.0 to 2.5.0 by @dependabot[bot] in https://github.com/basementdevs/filament-webhooks/pull/5

### New Contributors

* @dependabot[bot] made their first contribution in https://github.com/basementdevs/filament-webhooks/pull/5

**Full Changelog**: https://github.com/basementdevs/filament-webhooks/compare/0.1.2...5.0.0

## 5.x - 2026-03-02

### What's Changed

* chore(deps): bump dependabot/fetch-metadata from 2.4.0 to 2.5.0 by @dependabot[bot] in https://github.com/basementdevs/filament-webhooks/pull/5

### New Contributors

* @dependabot[bot] made their first contribution in https://github.com/basementdevs/filament-webhooks/pull/5

**Full Changelog**: https://github.com/basementdevs/filament-webhooks/compare/0.1.2...5.x

## 0.1.2 - 2025-12-17

### What's Changed

* feat(admin): widgets per source by @PilsAraujo in https://github.com/basementdevs/filament-webhooks/pull/2

### New Contributors

* @PilsAraujo made their first contribution in https://github.com/basementdevs/filament-webhooks/pull/2

**Full Changelog**: https://github.com/basementdevs/filament-webhooks/compare/0.1.1...0.1.2

## 0.1.1 - 2025-09-17

downgrading base php version
**Full Changelog**: https://github.com/basementdevs/filament-webhooks/compare/0.1.0...0.1.1

## 0.1.0 - 2025-09-15

yay
