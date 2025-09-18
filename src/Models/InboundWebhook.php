<?php

declare(strict_types=1);

namespace Basement\Webhooks\Models;

use Basement\Webhooks\Database\Factories\InboundWebhookFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    ];

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'payload' => 'array',
            'source' => config('filament-webhooks.providers_enum'),
        ];
    }

    protected static function newFactory(): InboundWebhookFactory
    {
        return InboundWebhookFactory::new();
    }
}
