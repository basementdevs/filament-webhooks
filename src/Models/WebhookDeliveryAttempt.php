<?php

declare(strict_types=1);

namespace Basement\Webhooks\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class WebhookDeliveryAttempt extends Model
{
    use HasUuids;

    protected $table = 'webhook_delivery_attempts';

    protected $fillable = [
        'inbound_webhook_id',
        'action',
        'status',
        'error_message',
    ];

    public function inboundWebhook(): BelongsTo
    {
        return $this->belongsTo(InboundWebhook::class);
    }
}
