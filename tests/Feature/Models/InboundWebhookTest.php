<?php

declare(strict_types=1);

use Basement\Webhooks\Enums\InboundWebhookSource;
use Basement\Webhooks\Models\InboundWebhook;
use Illuminate\Support\Str;

describe('InboundWebhook Model', function (): void {

    it('uses uuid as primary key', function (): void {
        $webhook = InboundWebhook::factory()->create();

        expect(Str::isUuid($webhook->id))->toBeTrue();
    });

    it('supports soft deletes', function (): void {
        $webhook = InboundWebhook::factory()->create();
        $webhook->delete();

        expect($webhook->trashed())->toBeTrue()
            ->and(InboundWebhook::withTrashed()->find($webhook->id))->not->toBeNull()
            ->and(InboundWebhook::find($webhook->id))->toBeNull();
    });

    it('casts source to configured enum', function (): void {
        $webhook = InboundWebhook::factory()->create(['source' => 'resend']);

        expect($webhook->source)->toBe(InboundWebhookSource::Resend);
    });

    it('casts payload to array', function (): void {
        $webhook = InboundWebhook::factory()->create();

        expect($webhook->payload)->toBeArray()
            ->and($webhook->payload)->toHaveKey('id');
    });

    it('can be created via factory', function (): void {
        $webhooks = InboundWebhook::factory()->count(5)->create();

        expect($webhooks)->toHaveCount(5)
            ->and(InboundWebhook::count())->toBe(5);
    });

    it('has fillable attributes', function (): void {
        $webhook = InboundWebhook::factory()->create([
            'source' => InboundWebhookSource::Autentique,
            'event' => 'signature.signed',
            'url' => 'https://test.com/hook',
            'payload' => ['test' => true],
        ]);

        expect($webhook->source)->toBe(InboundWebhookSource::Autentique)
            ->and($webhook->event)->toBe('signature.signed')
            ->and($webhook->url)->toBe('https://test.com/hook')
            ->and($webhook->payload)->toBe(['test' => true]);
    });
});
