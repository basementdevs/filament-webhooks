<?php

declare(strict_types=1);

namespace Basement\Webhooks\Contracts;

use Basement\Webhooks\Models\InboundWebhook;

interface StoresInboundWebhook
{
    public function store(InboundWebhookContract $source, string $event, string $url, array $payload): InboundWebhook;
}
