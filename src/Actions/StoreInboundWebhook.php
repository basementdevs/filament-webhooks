<?php

declare(strict_types=1);

namespace Basement\Webhooks\Actions;

use Basement\Webhooks\Contracts\InboundWebhookContract;
use Basement\Webhooks\Models\InboundWebhook;

final class StoreInboundWebhook
{
    public function store(InboundWebhookContract $source, string $event, string $url, array|string $payload): void
    {
        InboundWebhook::query()->create([
            'source' => $source->value,
            'event' => $event,
            'url' => $url,
            'payload' => is_array($payload) ? json_encode($payload) : $payload,
        ]);
    }
}
