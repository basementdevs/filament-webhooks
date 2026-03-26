<?php

declare(strict_types=1);

use Basement\Webhooks\FilamentWebhookPlugin;

describe('FilamentWebhookPlugin', function (): void {

    it('has the correct plugin id', function (): void {
        $plugin = FilamentWebhookPlugin::make();

        expect($plugin->getId())->toBe('filament-webhooks');
    });

    it('can be instantiated via make', function (): void {
        $plugin = FilamentWebhookPlugin::make();

        expect($plugin)->toBeInstanceOf(FilamentWebhookPlugin::class);
    });
});
