<?php

declare(strict_types=1);

use Basement\Webhooks\Filament\Admin\Resources\InboundWebhook\InboundWebhookResource;
use Basement\Webhooks\Models\InboundWebhook;

describe('List Inbound Webhooks', function (): void {

    it('resource uses the configured model', function (): void {
        expect(InboundWebhookResource::getModel())
            ->toBe(InboundWebhook::class);
    });

    it('resource has the correct pages', function (): void {
        $pages = InboundWebhookResource::getPages();

        expect($pages)->toHaveKeys(['index', 'view'])
            ->and($pages)->not->toHaveKey('create')
            ->and($pages)->not->toHaveKey('edit');
    });

    it('resource respects view_any config', function (): void {
        config()->set('filament-webhooks.view_any', true);
        expect(InboundWebhookResource::canViewAny())->toBeTrue();

        config()->set('filament-webhooks.view_any', false);
        expect(InboundWebhookResource::canViewAny())->toBeFalse();
    });

    it('resource navigation is configurable', function (): void {
        config()->set('filament-webhooks.navigation_group', 'System');
        config()->set('filament-webhooks.navigation_label', 'Webhook Logs');
        config()->set('filament-webhooks.navigation_sort', 50);

        expect(InboundWebhookResource::getNavigationGroup())->toBe('System')
            ->and(InboundWebhookResource::getNavigationLabel())->toBe('Webhook Logs')
            ->and(InboundWebhookResource::getNavigationSort())->toBe(50);
    });

    it('resource shows navigation badge for recent webhooks', function (): void {
        expect(InboundWebhookResource::getNavigationBadge())->toBeNull();

        InboundWebhook::factory()->count(3)->create();

        expect(InboundWebhookResource::getNavigationBadge())->toBe('3');
    });

    it('resource eloquent query includes soft deleted records', function (): void {
        $webhook = InboundWebhook::factory()->create();
        $webhook->delete();

        $query = InboundWebhookResource::getEloquentQuery();
        expect($query->count())->toBe(1);
    });
});
