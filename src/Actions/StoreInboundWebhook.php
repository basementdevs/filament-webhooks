<?php

declare(strict_types=1);

namespace Basement\Webhooks\Actions;

use Basement\Webhooks\Contracts\InboundWebhookContract;
use Basement\Webhooks\Contracts\StoresInboundWebhook;
use Basement\Webhooks\Events\InboundWebhookReceived;
use Basement\Webhooks\Models\InboundWebhook;

final class StoreInboundWebhook implements StoresInboundWebhook
{
    public function store(InboundWebhookContract $source, string $event, string $url, array $payload): InboundWebhook
    {
        $webhook = InboundWebhook::query()->create([
            'source' => $source->value,
            'event' => $event,
            'url' => $url,
            'payload' => $payload,
        ]);

        InboundWebhookReceived::dispatch($webhook);

        return $webhook;
    }
}
