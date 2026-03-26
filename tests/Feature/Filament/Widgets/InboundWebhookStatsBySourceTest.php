<?php

declare(strict_types=1);

use Basement\Webhooks\Enums\InboundWebhookSource;
use Basement\Webhooks\Filament\Admin\Widgets\InboundWebhookStatsBySource;
use Basement\Webhooks\Models\InboundWebhook;

describe('InboundWebhookStatsBySource Widget', function (): void {

    it('returns stats for all enum cases', function (): void {
        actingAsAdmin();
        InboundWebhook::factory()->count(3)->create(['source' => InboundWebhookSource::Resend]);
        InboundWebhook::factory()->count(2)->create(['source' => InboundWebhookSource::Autentique]);

        $widget = new InboundWebhookStatsBySource;
        $stats = invade($widget)->getInboundWebhooksStats();

        expect($stats)->toHaveCount(count(InboundWebhookSource::cases()));
    });

    it('returns empty stats when no webhooks exist', function (): void {
        actingAsAdmin();

        $widget = new InboundWebhookStatsBySource;
        $stats = invade($widget)->getInboundWebhooksStats();

        expect($stats)->toHaveCount(count(InboundWebhookSource::cases()));

        foreach ($stats as $stat) {
            expect($stat->getValue())->toBe('0%');
        }
    });

    it('calculates correct percentages', function (): void {
        actingAsAdmin();
        InboundWebhook::factory()->count(3)->create(['source' => InboundWebhookSource::Resend]);
        InboundWebhook::factory()->count(7)->create(['source' => InboundWebhookSource::Autentique]);

        $widget = new InboundWebhookStatsBySource;
        $stats = invade($widget)->getInboundWebhooksStats();

        $values = collect($stats)->mapWithKeys(fn ($stat) => [$stat->getLabel() => $stat->getValue()]);

        expect($values->get('Resend'))->toBe('30%')
            ->and($values->get('Autentique'))->toBe('70%');
    });
});
