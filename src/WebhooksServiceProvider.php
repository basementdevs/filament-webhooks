<?php

declare(strict_types=1);

namespace Basement\Webhooks;

use Basement\Webhooks\Models\InboundWebhook;
use Illuminate\Database\Eloquent\Relations\Relation;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class WebhooksServiceProvider extends PackageServiceProvider
{
    public function boot(): void
    {
        parent::boot();

        Relation::morphMap([
            'inbound_webhook' => InboundWebhook::class,
        ]);

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-webhooks')
            ->hasConfigFile()
            ->discoversMigrations();
    }

    protected function getPackageProviders($app)
    {
        return [
            self::class,
        ];
    }
}
