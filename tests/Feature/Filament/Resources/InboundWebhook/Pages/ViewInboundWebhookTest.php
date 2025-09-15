<?php

declare(strict_types=1);

use Basement\Webhooks\Filament\Admin\Resources\InboundWebhook\Pages\ViewInboundWebhook;
use Basement\Webhooks\Models\InboundWebhook;

use function Pest\Livewire\livewire;

describe('View Inbound Webhook', function (): void {

    it('can see inbound webhook view', function (): void {
        // Arrange
        $webhook = InboundWebhook::factory()->create();

        livewire(ViewInboundWebhook::class, [
            'record' => $webhook->id,
        ])
            ->assertOk()
            ->assertSee($webhook->source->getLabel())
            ->assertSee($webhook->event)
            ->assertSee($webhook->url);
    });
})->skip();
