<?php

declare(strict_types=1);

use Basement\Webhooks\Enums\InboundWebhookSource;
use Basement\Webhooks\Models\InboundWebhook;

describe('View Inbound Webhook', function (): void {

    it('model casts payload to array', function (): void {
        $webhook = InboundWebhook::factory()->create();

        expect($webhook->payload)->toBeArray();
    });

    it('model casts source to enum', function (): void {
        $webhook = InboundWebhook::factory()->create();

        expect($webhook->source)->toBeInstanceOf(InboundWebhookSource::class);
    });

    it('payload can be pretty-printed as json', function (): void {
        $webhook = InboundWebhook::factory()->create();

        $json = json_encode($webhook->payload, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);

        expect($json)->toBeString()
            ->and(json_decode($json, true))->toBeArray();
    });
});
