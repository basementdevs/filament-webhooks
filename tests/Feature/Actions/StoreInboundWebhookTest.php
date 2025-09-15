<?php

declare(strict_types=1);

use Basement\Webhooks\Actions\StoreInboundWebhook;
use Basement\Webhooks\Enums\InboundWebhookSource;
use Basement\Webhooks\Models\InboundWebhook;

use function Pest\Laravel\assertDatabaseHas;

it('store webhook', function (): void {

    $action = new StoreInboundWebhook;

    $action->store(
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
});
