<?php

declare(strict_types=1);

use Basement\Webhooks\Actions\StoreInboundWebhook;
use Basement\Webhooks\Enums\InboundWebhookSource;
use Basement\Webhooks\Events\InboundWebhookReceived;
use Basement\Webhooks\Models\InboundWebhook;
use Illuminate\Support\Facades\Event;

use function Pest\Laravel\assertDatabaseHas;

describe('StoreInboundWebhook', function (): void {

    it('stores a webhook with array payload', function (): void {
        $action = new StoreInboundWebhook;

        $webhook = $action->store(
            InboundWebhookSource::Resend,
            'email.delivered',
            'https://example.com/webhook',
            ['key' => 'value']
        );

        assertDatabaseHas(InboundWebhook::class, [
            'source' => 'resend',
            'event' => 'email.delivered',
            'url' => 'https://example.com/webhook',
        ]);

        expect($webhook)->toBeInstanceOf(InboundWebhook::class)
            ->and($webhook->payload)->toBeArray()
            ->and($webhook->payload)->toBe(['key' => 'value']);
    });

    it('stores a webhook with json string payload', function (): void {
        $action = new StoreInboundWebhook;

        $webhook = $action->store(
            InboundWebhookSource::Autentique,
            'signature.accepted',
            'https://example.com/webhook',
            '{"key": "value"}'
        );

        expect($webhook->payload)->toBeArray()
            ->and($webhook->payload)->toBe(['key' => 'value']);
    });

    it('throws JsonException on invalid json string payload', function (): void {
        $action = new StoreInboundWebhook;

        $action->store(
            InboundWebhookSource::Resend,
            'email.delivered',
            'https://example.com/webhook',
            'not valid json'
        );
    })->throws(JsonException::class);

    it('returns the created model', function (): void {
        $action = new StoreInboundWebhook;

        $webhook = $action->store(
            InboundWebhookSource::Resend,
            'email.delivered',
            'https://example.com/webhook',
            ['test' => true]
        );

        expect($webhook)->toBeInstanceOf(InboundWebhook::class)
            ->and($webhook->exists)->toBeTrue()
            ->and($webhook->source)->toBe(InboundWebhookSource::Resend)
            ->and($webhook->event)->toBe('email.delivered');
    });

    it('dispatches InboundWebhookReceived event', function (): void {
        Event::fake([InboundWebhookReceived::class]);

        $action = new StoreInboundWebhook;

        $webhook = $action->store(
            InboundWebhookSource::Resend,
            'email.delivered',
            'https://example.com/webhook',
            ['key' => 'value']
        );

        Event::assertDispatched(InboundWebhookReceived::class, function ($event) use ($webhook) {
            return $event->webhook->id === $webhook->id;
        });
    });
});
