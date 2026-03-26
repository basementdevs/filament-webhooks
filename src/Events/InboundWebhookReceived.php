<?php

declare(strict_types=1);

namespace Basement\Webhooks\Events;

use Basement\Webhooks\Models\InboundWebhook;
use Illuminate\Foundation\Events\Dispatchable;

final class InboundWebhookReceived
{
    use Dispatchable;

    public function __construct(
        public readonly InboundWebhook $webhook,
    ) {}
}
