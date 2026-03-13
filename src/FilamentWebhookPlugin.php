<?php

declare(strict_types=1);

namespace Basement\Webhooks;

use Basement\Webhooks\Filament\Admin\Resources\InboundWebhook\InboundWebhookResource;
use Filament\Contracts\Plugin;
use Filament\Panel;

final class FilamentWebhookPlugin implements Plugin
{
    public static function make(): self
    {
        return app(self::class);
    }

    public function getId(): string
    {
        return 'filament-webhooks';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            InboundWebhookResource::class,
        ]);
    }

    public function boot(Panel $panel): void {}
}
