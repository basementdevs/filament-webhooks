<?php

declare(strict_types=1);

namespace Basement\Webhooks\Models;

use Basement\Webhooks\Database\Factories\InboundWebhookFactory;
use Basement\Webhooks\Enums\InboundWebhookStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class InboundWebhook extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'source',
        'event',
        'url',
        'payload',
        'status',
        'processed_at',
        'error_message',
    ];

    protected static function newFactory(): InboundWebhookFactory
    {
        return InboundWebhookFactory::new();
    }

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'payload' => 'array',
            'source' => config('filament-webhooks.providers_enum'),
            'status' => InboundWebhookStatus::class,
            'processed_at' => 'datetime',
        ];
    }

    public function deliveryAttempts(): HasMany
    {
        return $this->hasMany(WebhookDeliveryAttempt::class);
    }
}
