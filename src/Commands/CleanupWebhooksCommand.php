<?php

declare(strict_types=1);

namespace Basement\Webhooks\Commands;

use Basement\Webhooks\Models\InboundWebhook;
use Illuminate\Console\Command;

final class CleanupWebhooksCommand extends Command
{
    protected $signature = 'webhooks:cleanup {--days= : Number of days to retain}';

    protected $description = 'Delete inbound webhooks older than the configured retention period';

    public function handle(): int
    {
        $days = $this->option('days') ?? config('filament-webhooks.retention_days');

        if ($days === null) {
            $this->info('No retention period configured. Skipping cleanup.');

            return self::SUCCESS;
        }

        $days = (int) $days;

        /** @var class-string<InboundWebhook> $modelClass */
        $modelClass = config('filament-webhooks.model', InboundWebhook::class);

        $count = $modelClass::where('created_at', '<', now()->subDays($days))->forceDelete();

        $this->info("Deleted {$count} webhooks older than {$days} days.");

        return self::SUCCESS;
    }
}
