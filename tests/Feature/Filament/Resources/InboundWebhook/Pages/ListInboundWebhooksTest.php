<?php

declare(strict_types=1);

use Basement\Webhooks\Filament\Admin\Resources\InboundWebhook\Pages\ListInboundWebhooks;
use Basement\Webhooks\Models\InboundWebhook;

use function Pest\Livewire\livewire;

describe('List Inbound Webhooks', function (): void {

    it('list inbound webhook', function (): void {
        // Arrange
        actingAsAdmin();
        $webhooks = InboundWebhook::factory()->count(3)->create();

        // Act & Assert
        livewire(ListInboundWebhooks::class)
            ->assertOk()
            ->assertCanSeeTableRecords($webhooks);
    });
})->skip();
